<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const form = useForm({ title: '', comment: '', name: '', order: 0, homepage: false, featured: false });

function submit() {
    form.post('/internal/testimonials');
}
</script>

<template>
    <Head title="Create Testimonial" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/internal/testimonials"
                      class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </Link>
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Create Testimonial</h1>
                    <p class="text-sm text-warm-500 mt-0.5">Add a new member testimonial</p>
                </div>
            </div>
        </template>

        <div class="max-w-lg">
            <div class="bg-white border border-warm-200 rounded-2xl p-7 shadow-sm">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="title" class="block text-xs font-semibold text-warm-700 mb-1.5">Title</label>
                        <input v-model="form.title" id="title" type="text" required placeholder="e.g. A welcoming place"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                      focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                               :class="form.errors.title ? 'border-rose-300' : 'border-warm-200'" />
                        <p v-if="form.errors.title" class="mt-1.5 text-xs text-rose-600">{{ form.errors.title }}</p>
                    </div>

                    <div>
                        <label for="comment" class="block text-xs font-semibold text-warm-700 mb-1.5">Comment</label>
                        <textarea v-model="form.comment" id="comment" rows="4" required placeholder="What did they say?"
                                  class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                         focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300 resize-y"
                                  :class="form.errors.comment ? 'border-rose-300' : 'border-warm-200'"></textarea>
                        <p v-if="form.errors.comment" class="mt-1.5 text-xs text-rose-600">{{ form.errors.comment }}</p>
                    </div>

                    <div>
                        <label for="name" class="block text-xs font-semibold text-warm-700 mb-1.5">Name</label>
                        <input v-model="form.name" id="name" type="text" required placeholder="e.g. Jane Doe"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                      focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                               :class="form.errors.name ? 'border-rose-300' : 'border-warm-200'" />
                        <p v-if="form.errors.name" class="mt-1.5 text-xs text-rose-600">{{ form.errors.name }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 items-start">
                        <div>
                            <label for="order" class="block text-xs font-semibold text-warm-700 mb-1.5">Order</label>
                            <input v-model="form.order" id="order" type="number" min="0"
                                   class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                          focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                                   :class="form.errors.order ? 'border-rose-300' : 'border-warm-200'" />
                            <p v-if="form.errors.order" class="mt-1.5 text-xs text-rose-600">{{ form.errors.order }}</p>
                        </div>
                        <div class="mt-7 space-y-3">
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input v-model="form.homepage" type="checkbox"
                                       class="w-4 h-4 rounded border-warm-300 text-brand-600 focus:ring-brand-400" />
                                <span class="text-sm font-medium text-warm-700">Show on homepage</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input v-model="form.featured" type="checkbox"
                                       class="w-4 h-4 rounded border-warm-300 text-brand-600 focus:ring-brand-400" />
                                <span class="text-sm font-medium text-warm-700">Featured</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="px-6 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors shadow-sm disabled:opacity-50">
                            {{ form.processing ? 'Creating…' : 'Create testimonial' }}
                        </button>
                        <Link href="/internal/testimonials"
                              class="px-6 py-2.5 text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
