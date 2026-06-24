<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Internal/Testimonials/Index', [
            'testimonials' => Testimonial::orderBy('order')->latest('id')->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Internal/Testimonials/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $testimonial = Testimonial::create($validated);

        return to_route('internal.testimonials.index')
            ->with('success', 'Testimonial "' . $testimonial->title . '" created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        return Inertia::render('Internal/Testimonials/Edit', [
            'testimonial' => $testimonial,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $this->validateData($request);

        $testimonial->update($validated);

        return to_route('internal.testimonials.index')
            ->with('success', 'Testimonial "' . $testimonial->title . '" updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        $title = $testimonial->title;
        $testimonial->delete();

        return to_route('internal.testimonials.index')
            ->with('success', 'Testimonial "' . $title . '" deleted successfully.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title'    => 'required|string|max:255',
            'comment'  => 'required|string',
            'name'     => 'required|string|max:255',
            'order'    => 'nullable|integer|min:0',
            'homepage' => 'boolean',
            'featured' => 'boolean',
        ]);
    }
}
