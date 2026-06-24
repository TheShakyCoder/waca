<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const form = useForm({ title: '', description: '', icon: '', order: 0 });

function submit() {
    form.post('/internal/services');
}
</script>

<template>
    <Head title="Create Service" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/internal/services"
                      class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </Link>
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Create Service</h1>
                    <p class="text-sm text-warm-500 mt-0.5">Add a new “What We Do” item</p>
                </div>
            </div>
        </template>

        <div class="max-w-lg">
            <div class="bg-white border border-warm-200 rounded-2xl p-7 shadow-sm">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="title" class="block text-xs font-semibold text-warm-700 mb-1.5">Title</label>
                        <input v-model="form.title" id="title" type="text" required placeholder="e.g. Community Café"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                      focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                               :class="form.errors.title ? 'border-rose-300' : 'border-warm-200'" />
                        <p v-if="form.errors.title" class="mt-1.5 text-xs text-rose-600">{{ form.errors.title }}</p>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-semibold text-warm-700 mb-1.5">Description</label>
                        <textarea v-model="form.description" id="description" rows="3" required placeholder="Short description of this service"
                                  class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                         focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300 resize-y"
                                  :class="form.errors.description ? 'border-rose-300' : 'border-warm-200'"></textarea>
                        <p v-if="form.errors.description" class="mt-1.5 text-xs text-rose-600">{{ form.errors.description }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="icon" class="block text-xs font-semibold text-warm-700 mb-1.5">Icon <span class="text-warm-400 font-normal">(emoji)</span></label>
                            <input v-model="form.icon" id="icon" type="text" placeholder="e.g. 🍽️"
                                   class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                          focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                                   :class="form.errors.icon ? 'border-rose-300' : 'border-warm-200'" />
                            <p v-if="form.errors.icon" class="mt-1.5 text-xs text-rose-600">{{ form.errors.icon }}</p>
                        </div>
                        <div>
                            <label for="order" class="block text-xs font-semibold text-warm-700 mb-1.5">Order</label>
                            <input v-model="form.order" id="order" type="number" min="0"
                                   class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                          focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                                   :class="form.errors.order ? 'border-rose-300' : 'border-warm-200'" />
                            <p v-if="form.errors.order" class="mt-1.5 text-xs text-rose-600">{{ form.errors.order }}</p>
                        </div>
                    </div>

                    <p class="flex items-center gap-2 text-xs text-warm-400">
                        <span class="w-4 h-4 rounded-full bg-gradient-to-br from-rose-200 via-amber-200 to-sky-200"></span>
                        A pastel colour is assigned automatically.
                    </p>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="px-6 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors shadow-sm disabled:opacity-50">
                            {{ form.processing ? 'Creating…' : 'Create service' }}
                        </button>
                        <Link href="/internal/services"
                              class="px-6 py-2.5 text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
