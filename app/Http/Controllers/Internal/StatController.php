<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Stat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Internal/Stats/Index', [
            'stats' => Stat::orderBy('order')->latest('id')->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Internal/Stats/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'icon'  => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        $stat = Stat::create($validated);

        return to_route('internal.stats.index')
            ->with('success', 'Stat "' . $stat->label . '" created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stat $stat)
    {
        return Inertia::render('Internal/Stats/Edit', [
            'stat' => $stat,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stat $stat)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'icon'  => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        $stat->update($validated);

        return to_route('internal.stats.index')
            ->with('success', 'Stat "' . $stat->label . '" updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stat $stat)
    {
        $label = $stat->label;
        $stat->delete();

        return to_route('internal.stats.index')
            ->with('success', 'Stat "' . $label . '" deleted successfully.');
    }
}
