<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class MeetingController extends Controller
{
    /**
     * Week-at-a-glance calendar of meetings for the next 7 days.
     */
    public function week(Request $request)
    {
        // Allow paging forward/back a week at a time; default to the current week.
        $offset = $request->integer('offset', 0);
        $from   = Carbon::today()->startOfDay()->addWeeks($offset);
        $to     = $from->copy()->addDays(6)->endOfDay();

        $meetings = Meeting::with('activity')
            ->where(function ($q) use ($from, $to) {
                // Non-recurring within range.
                $q->whereNull('recurrence')
                  ->whereBetween('starts_at', [$from, $to]);
            })
            ->orWhere(function ($q) use ($from, $to) {
                // Recurring that began on or before the end of the range and hasn't ended.
                $q->whereNotNull('recurrence')
                  ->where('starts_at', '<=', $to)
                  ->where(function ($q2) use ($from) {
                      $q2->whereNull('recurrence_ends_at')
                         ->orWhere('recurrence_ends_at', '>=', $from);
                  });
            })
            ->get();

        $occurrences = $meetings
            ->flatMap(fn ($m) => $m->occurrences($from, $to))
            ->sortBy('starts_at')
            ->values();

        // Build the 7 day buckets, keyed by Y-m-d.
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $from->copy()->addDays($i);
            $key = $day->toDateString();
            $days[] = [
                'date'     => $key,
                'meetings' => $occurrences->filter(fn ($o) => Carbon::parse($o['starts_at'])->toDateString() === $key)->values()->all(),
            ];
        }

        return Inertia::render('Internal/Meetings/Week', [
            'days'   => $days,
            'offset' => $offset,
            'range'  => [
                'from' => $from->toIso8601String(),
                'to'   => $to->toIso8601String(),
            ],
        ]);
    }

    public function index(Request $request)
    {
        $meetings = Meeting::with('activity')
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->activity_id, fn ($q, $id) => $q->where('activity_id', $id))
            ->orderBy('starts_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Internal/Meetings/Index', [
            'meetings'   => $meetings,
            'activities' => Activity::orderBy('title')->get(['id', 'title']),
            'filters'    => $request->only(['search', 'activity_id']),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Internal/Meetings/Create', [
            'activities'  => Activity::where('status', 'active')->orderBy('title')->get(['id', 'title']),
            'activity_id' => $request->activity_id,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'activity_id'        => ['nullable', 'uuid', 'exists:activities,id'],
            'starts_at'          => ['required', 'date'],
            'ends_at'            => ['nullable', 'date', 'after:starts_at'],
            'location'           => ['nullable', 'string', 'max:255'],
            'fee'                => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'description'        => ['nullable', 'string'],
            'recurrence'         => ['nullable', 'string', Rule::in(['weekly', 'fortnightly', 'monthly'])],
            'recurrence_ends_at' => ['nullable', 'date', 'after:starts_at'],
        ]);

        Meeting::create($validated);

        return to_route('internal.meetings.index')
            ->with('success', '"' . $validated['title'] . '" created successfully.');
    }

    public function edit(Meeting $meeting)
    {
        return Inertia::render('Internal/Meetings/Edit', [
            'meeting'    => $meeting,
            'activities' => Activity::where('status', 'active')->orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'activity_id'        => ['nullable', 'uuid', 'exists:activities,id'],
            'starts_at'          => ['required', 'date'],
            'ends_at'            => ['nullable', 'date', 'after:starts_at'],
            'location'           => ['nullable', 'string', 'max:255'],
            'fee'                => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'description'        => ['nullable', 'string'],
            'recurrence'         => ['nullable', 'string', Rule::in(['weekly', 'fortnightly', 'monthly'])],
            'recurrence_ends_at' => ['nullable', 'date', 'after:starts_at'],
        ]);

        $meeting->update($validated);

        return to_route('internal.meetings.index')
            ->with('success', '"' . $meeting->title . '" updated successfully.');
    }

    public function destroy(Meeting $meeting)
    {
        $title = $meeting->title;
        $meeting->delete();

        return to_route('internal.meetings.index')
            ->with('success', '"' . $title . '" deleted successfully.');
    }
}
