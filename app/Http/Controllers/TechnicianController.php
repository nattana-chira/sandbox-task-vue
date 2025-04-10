<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    // List all technicians
    public function index()
    {
        $technicians = Technician::with('skills')->get();
        return response()->json($technicians);
    }

    // Save technician
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'skills' => 'nullable|array',
            'skills.*' => 'string',
        ]);

        $technician = Technician::create([
            'name' => $request->name,
        ]);

        if ($request->has('skills')) {
            foreach ($request->skills as $skillName) {
                $technician->skills()->create(['name' => $skillName]);
            }
        }

        return response()->json($technician->load('skills'), 200);
    }

    // Show a single technician (optional)
    public function show($id)
    {
        $technician = Technician::with('skills')->find($id);

        if (!$technician) {
            return response()->json(['message' => 'Technician not found'], 404);
        }

        return response()->json($technician);
    }

    // Update a technician
    public function update(Request $request, $id)
    {
        $technician = Technician::find($id);

        if (!$technician) {
            return response()->json(['message' => 'Technician not found'], 404);
        }

        // Validate input data
        $request->validate([
            'name' => 'required|string|max:255',
            'skills' => 'required|array',
        ]);

        // Update technician details
        $technician->update([
            'name' => $request->name,
            'skills' => json_encode($request->skills),
        ]);

        // $technician = Technician::with('skills')->find($id);

        $technician->skills()->delete();

        foreach ($request->skills as $skillName) {
            $technician->skills()->create(['name' => $skillName]);
        }

        return response()->json($technician);
    }

    // Delete a technician
    public function destroy($id)
    {
        $technician = Technician::find($id);

        if (!$technician) {
            return response()->json(['message' => 'Technician not found'], 404);
        }

        $technician->delete();

        return response()->json(['message' => 'Technician deleted successfully']);
    }
  
}
