<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout/Index.vue';

const form = useForm({ label: '', value: '', icon: '', order: 0 });

function submit() {
    form.post('/internal/stats');
}
</script>

<template>
    <Head title="Create Stat" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/internal/stats"
                      class="p-1.5 rounded-lg text-warm-400 hover:text-warm-700 hover:bg-warm-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </Link>
                <div>
                    <h1 class="text-xl font-semibold text-warm-900 font-display">Create Stat</h1>
                    <p class="text-sm text-warm-500 mt-0.5">Add a new key figure</p>
                </div>
            </div>
        </template>

        <div class="max-w-lg">
            <div class="bg-white border border-warm-200 rounded-2xl p-7 shadow-sm">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="value" class="block text-xs font-semibold text-warm-700 mb-1.5">Value</label>
                        <input v-model="form.value" id="value" type="text" required placeholder="e.g. 500+"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                      focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                               :class="form.errors.value ? 'border-rose-300' : 'border-warm-200'" />
                        <p v-if="form.errors.value" class="mt-1.5 text-xs text-rose-600">{{ form.errors.value }}</p>
                    </div>

                    <div>
                        <label for="label" class="block text-xs font-semibold text-warm-700 mb-1.5">Label</label>
                        <input v-model="form.label" id="label" type="text" required placeholder="e.g. Members"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl bg-white text-warm-900
                                      focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition placeholder-warm-300"
                               :class="form.errors.label ? 'border-rose-300' : 'border-warm-200'" />
                        <p v-if="form.errors.label" class="mt-1.5 text-xs text-rose-600">{{ form.errors.label }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="icon" class="block text-xs font-semibold text-warm-700 mb-1.5">Icon <span class="text-warm-400 font-normal">(optional)</span></label>
                            <input v-model="form.icon" id="icon" type="text" placeholder="e.g. 👥"
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

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" :disabled="form.processing"
                                class="px-6 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors shadow-sm disabled:opacity-50">
                            {{ form.processing ? 'Creating…' : 'Create stat' }}
                        </button>
                        <Link href="/internal/stats"
                              class="px-6 py-2.5 text-sm font-medium text-warm-600 border border-warm-200 rounded-xl hover:bg-warm-50 transition-colors">
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
