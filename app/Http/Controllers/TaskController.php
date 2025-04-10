<?php

namespace App\Http\Controllers;

use App\Http\Dto\TaskDto;
use App\Models\Task;
use App\Models\Technician;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Match tasks by technicians
    public function match() 
    {
        // Get all tasks and sort them by urgency
        $tasks = Task::orderByRaw("FIELD(urgency, 'High', 'Medium', 'Low')")
            ->get();

        $skillNames = $tasks->pluck("required_skill")->toArray();

        $technicians = Technician::whereHas('skills', function ($query) use ($skillNames) {
            $query->whereIn('name', $skillNames);
        })->get();

        $totalWorkDays = 0;
        $workDays = [];

        foreach ($tasks as $task) {
            $workDays[$task->id] = [];
            
            $matchingTechnicians = $technicians->filter(function ($technician) use ($task) {
                return  in_array(
                    $task->required_skill,
                    $technician->skills->pluck('name')->toArray(), 
                );
            });

            if (count($matchingTechnicians) < $task->required_technicians) {
                return response()->json([
                    'message' => 'Not enough technicians for matching.'
                ], 400);
            }

            $task->technicians = $matchingTechnicians->toArray();
            $task->remain = $task->duration;

            $totalWorkDays += $task->duration;
        }

        for ($i = 0; $i < $totalWorkDays; $i++) {
            $technicians = Technician::with('skills')->get();
            $assignedIds = [];

            $workDays[$i] = [
                "tasks" => [],
            ];

            for ($j = 0; $j < count($tasks); $j++) {
                $task = $tasks[$j];
                $workDays[$i]['tasks'][$j] = new TaskDto($task);

                $matchTechnicians = $technicians->filter(function ($technician) use ($task, $assignedIds) {
                    $assigned = in_array($technician->id,  $assignedIds);
                    $skillMatched = in_array(
                        $task->required_skill,
                        $technician->skills->pluck('name')->toArray()
                    );

                    return !$assigned && $skillMatched;
                });

                if ($task->remain > 0 && (count($matchTechnicians) >= $task->required_technicians)) {
                    $assigned = $matchTechnicians->take($task->required_technicians)->values();
                    $workDays[$i]['tasks'][$j]->assigned = $assigned->toArray();
                    $assignedIds = array_merge($assignedIds, $assigned->pluck('id')->toArray());
                    $task->remain = $task->remain - 1;
                }
            }
        }

        $workDays = collect($workDays)->filter(function ($item) {
            return !empty($item);
        })->filter(function($item) {
            return !collect($item['tasks'])->every(function ($task) {
                return empty($task->assigned);
            });
        });

        return response()->json($workDays, 200);
    }

    // Create a new task
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'required_skill' => 'required|string',
            'urgency' => 'required|in:Low,Medium,High',
            'duration' => 'required|integer|between:1,3',
            'required_technicians' => 'required|integer',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'required_skill' => $request->required_skill,
            'urgency' => $request->urgency,
            'duration' => $request->duration,
            'required_technicians' => $request->required_technicians,
        ]);

        return response()->json($task, 200);
    }

    // List all tasks
    public function index()
    {
        $tasks = Task::get();
        return response()->json($tasks);
    }

    public function show($id)
    {

        $task = Task::findOrFail($id);

        // Return the task as a JSON response (if it's an API)
        return response()->json($task);
    }

    // Update a task
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'required_skill' => 'required|string|max:255',
            'urgency' => 'required|in:Low,Medium,High',
            'required_technicians' => 'required|integer|min:1',
            'duration' => 'required|integer|between:1,3',  // Duration validation between 1 and 3
            'required_technicians' => 'required|integer',
        ]);

        $task = Task::findOrFail($id);
        $task->update($request->all());

        return response()->json($task);
    }
  
    // Delete a task
    public function destroy($id)
    {
        Task::destroy($id);
        return response()->json(['message' => 'Task deleted']);
    }
}
