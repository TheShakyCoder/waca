<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Meeting;
use Illuminate\Database\Seeder;

/**
 * Imports the WACA "Our Timetable 2025" schedule into activities + recurring meetings.
 *
 * Source: https://www.woodvalecommunitycentre.org/app/uploads/2025/03/Timetable-2025.jpg
 *
 * Notes:
 *  - Meetings are anchored to a fixed week (w/c Mon 3 Mar 2025) so the weekly/monthly
 *    recurrence stays stable across reseeds. The recurrence expander projects them forward.
 *  - Times are stored as the clock times shown on the timetable (the app treats stored
 *    datetimes as UTC; adjust for BST in-app if required).
 *  - Fees: "FREE" → 0.00; sessions with no published price → null.
 */
class TimetableSeeder extends Seeder
{
    private const VENUE_CENTRE = 'Woodvale Community Centre, Meadow Lane, PR8 3RS';
    private const VENUE_REC    = 'WACA Recreation Centre, off Orchard Lane, PR8 3RG';
    private const VENUE_CHURCH = 'Ainsdale Evangelical Church';

    public function run(): void
    {
        $activities = [
            'craft-cuppa-chat'          => ['Craft, Cuppa & Chat',        'Bring along your current crafting project, learn and discover within the group, accompanied with a cuppa.'],
            'chair-based-exercise'      => ['Chair Based Exercise',       'Classes for people over 50 to increase mobility, improve balance and have fun!'],
            'youth-sessions'            => ['Youth Sessions',             'For ages 11–19 years. Varied sessions from sports, arts & crafts and trips out. Times may vary. Must book in advance.'],
            'afternoon-lunch-club'      => ['Afternoon Lunch Club',       'Every 1st Tuesday of the month. Enjoy a home cooked meal, make new friends & socialise. Must book in advance.'],
            'woodland-project'          => ['Woodland Project',           'Help around our local wooded area, maintaining paths with woodchip, litter picks & more.'],
            'community-cafe'            => ['Community Café',              'A lovely space for you, friends & family. Hot drinks with mixed refreshments — everyone welcome.'],
            'course-sessions'           => ['Course Sessions',            'For adults 18+. Short courses and varied sessions (subject to funding). Courses range from origami, painting, Japanese arts & crafts, cookery, first aid & more. Times may vary.'],
            'ainsdale-evangelical'      => ['Ainsdale Evangelical Church', 'Prayers & bible study. Please contact Steve on 07776 397717.'],
            'music-group'               => ['Music Group',                'Instruments & singing at the Recreation Centre. Please contact Catherine on 07520 323656.'],
            'ashleighs-dance-academy'   => ['Ashleigh\'s Dance Academy',  'Inclusive dance group for all abilities. See www.ashleighsdanceacademy.com for details.'],
        ];

        $activityIds = [];
        foreach ($activities as $slug => [$title, $description]) {
            $activityIds[$slug] = Activity::updateOrCreate(
                ['slug' => $slug],
                ['title' => $title, 'description' => $description, 'status' => 'active'],
            )->id;
        }

        // [date, start, end, activity slug, fee, recurrence, location]
        $meetings = [
            // Monday 22 Jun 2026
            ['2026-06-22', '10:00', '12:00', 'craft-cuppa-chat',     2.00, 'weekly', self::VENUE_CENTRE],
            ['2026-06-22', '14:00', '15:00', 'chair-based-exercise', 5.00, 'weekly', self::VENUE_CENTRE],
            ['2026-06-22', '17:00', '19:00', 'youth-sessions',       null, 'weekly', self::VENUE_CENTRE],

            // Tuesday 23 Jun 2026
            ['2026-06-23', '12:30', '13:30', 'afternoon-lunch-club', 0.00, 'monthly', self::VENUE_CENTRE],
            ['2026-06-23', '14:00', '15:00', 'chair-based-exercise', 5.00, 'weekly',  self::VENUE_CENTRE],
            ['2026-06-23', '17:00', '19:00', 'youth-sessions',       null, 'weekly',  self::VENUE_CENTRE],

            // Wednesday 24 Jun 2026
            ['2026-06-24', '10:00', '12:00', 'woodland-project',     0.00, 'weekly', self::VENUE_CENTRE],
            ['2026-06-24', '12:00', '14:00', 'community-cafe',       0.00, 'weekly', self::VENUE_CENTRE],
            ['2026-06-24', '14:30', '15:30', 'chair-based-exercise', 5.00, 'weekly', self::VENUE_CENTRE],

            ['2026-06-26', '13:30', '15:00', 'ainsdale-evangelical', null, 'weekly', self::VENUE_CHURCH],
            ['2026-06-26', '19:30', '22:00', 'music-group',          null, 'weekly', self::VENUE_REC],
            
            // Thursday 25 Jun 2026
            ['2026-06-25', '10:00', '12:00', 'course-sessions',      null, 'weekly', self::VENUE_CENTRE],
            
            // Saturday 8 Mar 2025
            ['2026-06-27', '13:00', '14:00', 'ashleighs-dance-academy', null, 'weekly', self::VENUE_CENTRE],
        ];

        foreach ($meetings as [$date, $start, $end, $slug, $fee, $recurrence, $location]) {
            [$title] = $activities[$slug];
            $startsAt = "{$date} {$start}:00";

            Meeting::updateOrCreate(
                ['title' => $title, 'starts_at' => $startsAt],
                [
                    'activity_id' => $activityIds[$slug],
                    'ends_at'     => "{$date} {$end}:00",
                    'location'    => $location,
                    'fee'         => $fee,
                    'recurrence'  => $recurrence,
                ],
            );
        }
    }
}
