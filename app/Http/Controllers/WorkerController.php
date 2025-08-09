<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::paginate(10);
        $totalWorkers = Worker::count();
        $activeWorkers = Worker::where('is_active', true)->count();
        $totalDailySalary = Worker::sum('daily_salary');

        return view('workers.index', compact('workers', 'totalWorkers', 'activeWorkers', 'totalDailySalary'));
    }

    public function create()
    {
        return view('workers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'         => 'required|string|max:10|unique:workers',
            'name'         => 'required|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'birth_date'   => 'nullable|date',
            'address'      => 'nullable|string',
            'daily_salary' => 'nullable|numeric',
            'is_active'    => 'boolean',
            'note'         => 'nullable|string',
        ]);

        Worker::create($validated);

        return redirect()->route('workers.index')->with('success', 'Tukang berhasil ditambahkan.');
    }

    public function show(Worker $worker)
    {
        return view('workers.show', compact('worker'));
    }

    public function edit(Worker $worker)
    {
        return view('workers.edit', compact('worker'));
    }

    public function update(Request $request, Worker $worker)
    {
        $validated = $request->validate([
            'code'         => 'required|string|max:10|unique:workers,code,' . $worker->id,
            'name'         => 'required|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'birth_date'   => 'nullable|date',
            'address'      => 'nullable|string',
            'daily_salary' => 'nullable|numeric',
            'is_active'    => 'boolean',
            'note'         => 'nullable|string',
        ]);

        $worker->update($validated);

        return redirect()->route('workers.index')->with('success', 'Tukang berhasil diperbarui.');
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        return redirect()->route('workers.index')->with('success', 'Tukang berhasil dihapus.');
    }
}
