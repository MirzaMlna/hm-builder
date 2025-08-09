<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::all();
        return response()->json($workers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'         => 'required|string|max:10|unique:workers',
            'name'         => 'required|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'birth_date'   => 'nullable|date',
            'address'      => 'nullable|string',
            'qr_code_path' => 'nullable|string|max:255',
            'photo'        => 'nullable|string|max:255',
            'daily_salary' => 'nullable|numeric',
            'is_active'    => 'boolean',
            'note'         => 'nullable|string',
        ]);

        $worker = Worker::create($validated);

        return response()->json($worker, 201);
    }

    public function show(Worker $worker)
    {
        return response()->json($worker);
    }

    public function update(Request $request, Worker $worker)
    {
        $validated = $request->validate([
            'code'         => 'required|string|max:10|unique:workers,code,' . $worker->id,
            'name'         => 'required|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'birth_date'   => 'nullable|date',
            'address'      => 'nullable|string',
            'qr_code_path' => 'nullable|string|max:255',
            'photo'        => 'nullable|string|max:255',
            'daily_salary' => 'nullable|numeric',
            'is_active'    => 'boolean',
            'note'         => 'nullable|string',
        ]);

        $worker->update($validated);

        return response()->json($worker);
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        return response()->json(null, 204);
    }
}
