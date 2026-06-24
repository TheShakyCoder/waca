<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Internal/Services/Index', [
            'services' => Service::orderBy('order')->latest('id')->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Internal/Services/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $service = Service::create([
            ...$this->validateData($request),
            'color' => Service::randomPastel(),
        ]);

        return to_route('internal.services.index')
            ->with('success', 'Service "' . $service->title . '" created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return Inertia::render('Internal/Services/Edit', [
            'service' => $service,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $service->update($this->validateData($request));

        return to_route('internal.services.index')
            ->with('success', 'Service "' . $service->title . '" updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $title = $service->title;
        $service->delete();

        return to_route('internal.services.index')
            ->with('success', 'Service "' . $title . '" deleted successfully.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'icon'        => 'nullable|string|max:255',
            'order'       => 'nullable|integer|min:0',
        ]);
    }
}
