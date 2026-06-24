<script setup>
import { computed } from 'vue';

const props = defineProps({
    events: { type: Array, default: () => [] },
});

// Rotating palette so consecutive cards look varied.
const palette = [
    { tagColor: 'bg-brand-100 text-brand-700',   accent: 'bg-brand-600' },
    { tagColor: 'bg-sky-100 text-sky-700',       accent: 'bg-sky-500' },
    { tagColor: 'bg-purple-100 text-purple-700', accent: 'bg-purple-500' },
    { tagColor: 'bg-accent-100 text-orange-700', accent: 'bg-accent-500' },
];

const fallback = [
    { date: 'SAT 12 APR', title: 'Spring Community Fair',  time: '10:00 – 16:00', location: 'Main Hall',      tag: 'Community' },
    { date: 'WED 16 APR', title: 'Senior Coffee Morning',  time: '09:30 – 11:30', location: 'Community Café', tag: 'Seniors' },
    { date: 'FRI 25 APR', title: 'Youth Film Night',       time: '18:00 – 21:00', location: 'Activity Room',  tag: 'Youth' },
    { date: 'SAT 3 MAY',  title: 'Family Fun Day',         time: '11:00 – 15:00', location: 'Outdoor Space',  tag: 'Family' },
];

function formatDate(iso) {
    return new Date(iso)
        .toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'short' })
        .toUpperCase()
        .replace(',', '');
}

function formatTime(start, end) {
    const opts = { hour: '2-digit', minute: '2-digit' };
    const s = new Date(start).toLocaleTimeString('en-GB', opts);
    return end ? `${s} – ${new Date(end).toLocaleTimeString('en-GB', opts)}` : s;
}

const items = computed(() => {
    const source = props.events.length
        ? props.events.map(e => ({
            date:     formatDate(e.starts_at),
            title:    e.title,
            time:     formatTime(e.starts_at, e.ends_at),
            location: e.location ?? 'TBC',
            fee:      e.fee === null || e.fee === undefined ? null : (Number(e.fee) === 0 ? 'Free' : `£${Number(e.fee).toFixed(2)}`),
            tag:      e.activity?.title ?? 'Event',
        }))
        : fallback;

    return source.map((e, i) => ({ ...e, ...palette[i % palette.length] }));
});
</script>

<template>
    <section id="events" class="py-20 bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-14">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-brand-600 mb-3 block">Upcoming
                        Events</span>
                    <h2 class="font-display text-3xl sm:text-4xl font-bold text-warm-900">
                        What's on
                    </h2>
                </div>
                <a href="/calendar"
                    class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-brand-600 hover:text-brand-800 transition-colors">
                    View all events
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div v-for="event in items" :key="event.title + event.date"
                    class="bg-white rounded-2xl overflow-hidden border border-warm-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="h-2 w-full" :class="event.accent"></div>
                    <div class="p-5">
                        <p class="text-xs font-bold tracking-wider text-warm-400 mb-3">{{ event.date }}</p>
                        <h3 class="font-semibold text-warm-900 mb-2 leading-snug">{{ event.title }}</h3>
                        <div class="flex items-center gap-1.5 text-xs text-warm-500 mb-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ event.time }}
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-warm-500 mb-4">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ event.location }}
                        </div>
                        <div v-if="event.fee" class="flex items-center gap-1.5 text-xs font-medium text-warm-600 mb-4">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ event.fee }}
                        </div>
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold"
                            :class="event.tagColor">
                            {{ event.tag }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>