<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const page = usePage();
defineProps({ services: Object });

// Legacy seeded rows store a palette key; new rows store a pastel hex.
const paletteHex = {
    brand: '#dcfce7', sky: '#e0f2fe', purple: '#ede9fe',
    accent: '#ffedd5', rose: '#ffe4e6', amber: '#fef3c7',
};

function iconStyle(color) {
    const hex = color?.startsWith('#') ? color : (paletteHex[color] ?? '#f1f5f9');
    return { backgroundColor: hex };
}

function destroy(service) {
    if (confirm(`Delete service "${service.title}"? This cannot be undone.`)) {
        router.delete(`/internal/services/${service.id}`, { preserveScroll: true });
    }
}
</script>

<template>
    <Head title="Services" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Services</h1>
                    <p class="text-sm text-warm-500 mt-0.5">Manage the “What We Do” section on the home page</p>
                </div>
                <Link href="/internal/services/create"
                      class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New service
                </Link>
            </div>
        </template>

        <!-- Flash -->
        <div v-if="page.props.flash.success"
             class="mb-6 flex items-center gap-2 px-4 py-3 bg-brand-50 border border-brand-200 rounded-xl text-sm text-brand-700">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ page.props.flash.success }}
        </div>

        <!-- Empty state -->
        <div v-if="!services?.data?.length"
             class="text-center py-20 bg-warm-50 rounded-2xl border border-warm-200">
            <span class="text-4xl block mb-3">✨</span>
            <p class="font-semibold text-warm-800 mb-1">No services yet</p>
            <p class="text-sm text-warm-500 mb-5">Add your first service to feature on the home page.</p>
            <Link href="/internal/services/create"
                  class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors">
                Create a service
            </Link>
        </div>

        <!-- Services grid -->
        <div v-else class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div v-for="service in services.data" :key="service.id"
                 class="group flex items-start gap-4 p-5 bg-white border border-warm-200 rounded-2xl hover:border-brand-300 hover:shadow-md transition-all">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl shrink-0"
                     :style="iconStyle(service.color)">
                    {{ service.icon || '✨' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-warm-900 text-sm truncate">{{ service.title }}</p>
                    <p class="text-sm text-warm-500 mt-1 line-clamp-2">{{ service.description }}</p>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <Link :href="`/internal/services/${service.id}/edit`"
                          class="p-1.5 rounded-lg text-warm-400 hover:text-brand-600 hover:bg-warm-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </Link>
                    <button type="button" @click="destroy(service)"
                            class="p-1.5 rounded-lg text-warm-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
