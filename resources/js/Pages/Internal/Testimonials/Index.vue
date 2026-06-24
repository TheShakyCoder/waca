<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const page = usePage();
defineProps({ testimonials: Object });

function destroy(testimonial) {
    if (confirm(`Delete testimonial "${testimonial.title}"? This cannot be undone.`)) {
        router.delete(`/internal/testimonials/${testimonial.id}`, { preserveScroll: true });
    }
}
</script>

<template>
    <Head title="Testimonials" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Testimonials</h1>
                    <p class="text-sm text-warm-500 mt-0.5">Manage what members say about the community</p>
                </div>
                <Link href="/internal/testimonials/create"
                      class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New testimonial
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
        <div v-if="!testimonials?.data?.length"
             class="text-center py-20 bg-warm-50 rounded-2xl border border-warm-200">
            <span class="text-4xl block mb-3">💬</span>
            <p class="font-semibold text-warm-800 mb-1">No testimonials yet</p>
            <p class="text-sm text-warm-500 mb-5">Add your first testimonial to feature member feedback.</p>
            <Link href="/internal/testimonials/create"
                  class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors">
                Create a testimonial
            </Link>
        </div>

        <!-- Testimonials list -->
        <div v-else class="space-y-4">
            <div v-for="testimonial in testimonials.data" :key="testimonial.id"
                 class="group flex items-start gap-4 p-5 bg-white border border-warm-200 rounded-2xl hover:border-brand-300 hover:shadow-md transition-all">
                <div class="w-11 h-11 rounded-xl bg-brand-100 flex items-center justify-center text-xl shrink-0">
                    💬
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-warm-900 text-sm truncate">{{ testimonial.title }}</p>
                        <span v-if="testimonial.homepage"
                              class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-medium shrink-0">
                            🏠 Homepage
                        </span>
                    </div>
                    <p class="text-sm text-warm-600 mt-1 line-clamp-2">“{{ testimonial.comment }}”</p>
                    <p class="text-xs text-warm-400 mt-1.5">— {{ testimonial.name }}</p>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <Link :href="`/internal/testimonials/${testimonial.id}/edit`"
                          class="p-1.5 rounded-lg text-warm-400 hover:text-brand-600 hover:bg-warm-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </Link>
                    <button type="button" @click="destroy(testimonial)"
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
