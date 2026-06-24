<script setup>
import { computed } from 'vue';

const props = defineProps({
    stats: { type: Array, default: () => [] },
});

const fallback = [
    { value: '30+', label: 'Years serving the community' },
    { value: '1,200+', label: 'Active members' },
    { value: '80+', label: 'Events per year' },
    { value: '150+', label: 'Dedicated volunteers' },
];

const items = computed(() => props.stats.length ? props.stats : fallback);
</script>

<template>
    <section class="bg-white py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="stat in items" :key="stat.label"
                    class="text-center p-6 rounded-2xl bg-brand-50 border border-brand-100">
                    <p class="font-display text-3xl font-bold text-brand-700">
                        <span v-if="stat.icon" class="mr-1">{{ stat.icon }}</span>{{ stat.value }}
                    </p>
                    <p class="text-sm text-warm-500 mt-1">{{ stat.label }}</p>
                </div>
            </div>
        </div>
    </section>
</template>