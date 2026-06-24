<script setup>
import { computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const props = defineProps({
    days:   { type: Array,  required: true },
    offset: { type: Number, default: 0 },
    range:  { type: Object, required: true },
});

const today = new Date().toLocaleDateString('en-CA'); // Y-m-d in local time

const rangeLabel = computed(() => {
    const opts = { day: 'numeric', month: 'short' };
    const from = new Date(props.range.from).toLocaleDateString('en-GB', opts);
    const to   = new Date(props.range.to).toLocaleDateString('en-GB', { ...opts, year: 'numeric' });
    return `${from} – ${to}`;
});

const totalMeetings = computed(() => props.days.reduce((sum, d) => sum + d.meetings.length, 0));

function dayName(date) {
    return new Date(date + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'short' });
}

function dayNumber(date) {
    return new Date(date + 'T00:00:00').toLocaleDateString('en-GB', { day: 'numeric' });
}

function time(iso) {
    return new Date(iso).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
}

function go(offset) {
    router.get(route('internal.meetings.week'), { offset }, { preserveScroll: true });
}
</script>

<template>
    <Head title="Meetings — Week View" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Week View</h1>
                    <p class="text-sm text-warm-500 mt-0.5">{{ rangeLabel }} · {{ totalMeetings }} meeting{{ totalMeetings === 1 ? '' : 's' }}</p>
                </div>
                <Link :href="route('internal.meetings.index')"
                      class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    List view
                </Link>
            </div>
        </template>

        <!-- Week navigation -->
        <div class="flex items-center justify-between mb-5">
            <button type="button" @click="go(offset - 1)"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Previous
            </button>
            <button v-if="offset !== 0" type="button" @click="go(0)"
                    class="px-3.5 py-2 text-sm font-medium text-brand-600 hover:text-brand-800 transition-colors">
                This week
            </button>
            <button type="button" @click="go(offset + 1)"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                Next
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <!-- Calendar grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3">
            <div v-for="day in days" :key="day.date"
                 class="flex flex-col bg-white border rounded-2xl overflow-hidden min-h-[8rem]"
                 :class="day.date === today ? 'border-brand-400 ring-1 ring-brand-200' : 'border-warm-200'">
                <div class="px-3 py-2.5 border-b text-center"
                     :class="day.date === today ? 'bg-brand-50 border-brand-100' : 'bg-warm-50 border-warm-100'">
                    <p class="text-[11px] font-semibold uppercase tracking-wide"
                       :class="day.date === today ? 'text-brand-600' : 'text-warm-400'">
                        {{ dayName(day.date) }}
                    </p>
                    <p class="text-lg font-bold font-display leading-tight"
                       :class="day.date === today ? 'text-brand-700' : 'text-warm-800'">
                        {{ dayNumber(day.date) }}
                    </p>
                </div>

                <div class="flex-1 p-2 space-y-2">
                    <p v-if="!day.meetings.length" class="text-[11px] text-warm-300 text-center pt-3">—</p>

                    <Link v-for="m in day.meetings" :key="m.id + m.starts_at"
                          :href="route('internal.meetings.edit', m.id)"
                          class="block p-2 rounded-lg bg-brand-50 border border-brand-100 hover:border-brand-300 hover:bg-brand-100/60 transition-colors">
                        <p class="text-[11px] font-semibold text-brand-700">{{ time(m.starts_at) }}</p>
                        <p class="text-xs font-medium text-warm-800 leading-snug line-clamp-2">{{ m.title }}</p>
                        <p v-if="m.location" class="text-[11px] text-warm-400 truncate mt-0.5">📍 {{ m.location }}</p>
                        <p v-if="m.activity" class="text-[11px] text-warm-400 truncate">{{ m.activity.title }}</p>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
