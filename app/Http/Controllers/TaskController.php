<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Technician;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function match() {
        $tasks = Task::all(); // Retrieve all tasks from the database
        $technicians = Technician::with('skills')->get(); // Retrieve all technicians from the database
    
        // Sort tasks based on urgency (High, Medium, Low)
        $tasks = $tasks->sortBy(function ($task) {
            return ['High' => 1, 'Medium' => 2, 'Low' => 3][$task->urgency];
        });
    
        $matchedTasks = [];
    
        // For each task, we try to find suitable technicians
        foreach ($tasks as $task) {
            $assignedTechnicians = [];
            $technicianAssignments = [];
    
            // Loop to assign technicians to the task
            $availableTechnicians = $technicians->filter(function ($technician) use ($task) {
                return in_array($task->required_skill, $technician->skills->pluck('name')->toArray());
            });
    
            $totalDaysRequired = $task->duration; // The number of days required for this task
    
            foreach ($availableTechnicians as $technician) {
                // Check if technician is available for the required number of days
                if (count($assignedTechnicians) < $task->required_technicians) {
                    // Check technician's availability for each day in the task's duration
                    $assignedTechnicians[] = [
                        'id' => $technician->id,
                        'name' => $technician->name,
                        'workDays' => $totalDaysRequired,
                    ];
                    $technicianAssignments[$technician->id] = $technician->name;
                }
            }
    
            if (count($assignedTechnicians) < $task->required_technicians) {
                return response()->json(['error' => 'Not enough technicians available for task'], 400);
            }
    
            // Store the task with assigned technicians
            $matchedTasks[] = [
                'id' => $task->id,
                'title' => $task->title,
                'required_skill' => $task->required_skill,
                'urgency' => $task->urgency,
                'duration' => $task->duration,
                'required_technicians' => $task->required_technicians,
                'assignedTechnicians' => $assignedTechnicians,
            ];
        }
    
        return response()->json(['tasks' => $matchedTasks]);
    }

    private function getAvailableTechniciansForTask($task, $technicians)
    {
        // Find technicians who have the required skill
        return $technicians->filter(function ($technician) use ($task) {
            return in_array($task->required_skill, $technician->skills->pluck('name')->toArray());
        })->values();
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
