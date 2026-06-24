<script setup>
import { computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import Header from '@/Layouts/Partials/Header.vue';
import Footer from '@/Layouts/Partials/Footer.vue';

const page = usePage();
const navLinks = page.props.site.nav_links;

const props = defineProps({
    days:   { type: Array,  required: true },
    offset: { type: Number, default: 0 },
    range:  { type: Object, required: true },
});

const today = new Date().toLocaleDateString('en-CA'); // Y-m-d, local

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

function feeLabel(fee) {
    if (fee === null || fee === undefined) return null;
    return Number(fee) === 0 ? 'Free' : `£${Number(fee).toFixed(2)}`;
}

function go(offset) {
    router.get('/meetings', { offset }, { preserveScroll: true, preserveState: true });
}
</script>

<template>
    <Head title="What's On — This Week" />

    <div class="font-sans antialiased text-warm-800 bg-white">
        <Header :navLinks="navLinks" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <!-- Heading -->
            <div class="text-center mb-8">
                <span class="text-xs font-semibold uppercase tracking-widest text-brand-600 mb-3 block">What's On</span>
                <h1 class="font-display text-3xl sm:text-4xl font-bold text-warm-900">This Week</h1>
                <p class="text-warm-500 mt-3">{{ rangeLabel }} · {{ totalMeetings }} session{{ totalMeetings === 1 ? '' : 's' }}</p>
            </div>

            <!-- Week navigation -->
            <div class="flex items-center justify-between mb-6">
                <button type="button" @click="go(offset - 1)"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Previous
                </button>
                <button v-if="offset !== 0" type="button" @click="go(0)"
                        class="px-4 py-2 text-sm font-medium text-brand-600 hover:text-brand-800 transition-colors">
                    This week
                </button>
                <button type="button" @click="go(offset + 1)"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                    Next
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Week grid -->
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

                        <div v-for="m in day.meetings" :key="m.id + m.starts_at"
                             class="p-2 rounded-lg bg-brand-50 border border-brand-100">
                            <p class="text-[11px] font-semibold text-brand-700">{{ time(m.starts_at) }}<span v-if="m.ends_at"> – {{ time(m.ends_at) }}</span></p>
                            <p class="text-xs font-medium text-warm-800 leading-snug">{{ m.title }}</p>
                            <p v-if="m.location" class="text-[11px] text-warm-400 truncate mt-0.5">📍 {{ m.location }}</p>
                            <p v-if="feeLabel(m.fee)" class="text-[11px] font-medium text-warm-500">{{ feeLabel(m.fee) }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <Footer />
    </div>
</template>
