<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Harvest;
use Illuminate\Http\Request;

class HarvestController extends Controller
{
    public function index(Request $request)
    {
        $query = Harvest::with(['device', 'user']);

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $harvests = $query->latest('date')->paginate(10);
        return view('harvests.index', compact('harvests'));
    }

    public function create()
    {
        $devices = Device::all();
        return view('harvests.create', compact('devices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'quality' => 'required|string',
            'device_id' => 'nullable|string|exists:devices,id',
        ]);

        $validated['user_id'] = auth()->id();

        Harvest::create($validated);

        return redirect()->route('harvests.index')->with('success', 'Harvest data recorded.');
    }

    public function edit(Harvest $harvest)
    {
        $devices = Device::all();
        return view('harvests.edit', compact('harvest', 'devices'));
    }

    public function update(Request $request, Harvest $harvest)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'quality' => 'required|string',
            'device_id' => 'nullable|string|exists:devices,id',
        ]);

        $harvest->update($validated);

        return redirect()->route('harvests.index')->with('success', 'Harvest data updated.');
    }

    public function destroy(Harvest $harvest)
    {
        $harvest->delete();
        return redirect()->route('harvests.index')->with('success', 'Harvest data deleted.');
    }
}
