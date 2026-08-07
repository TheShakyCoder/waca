<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const posts = ref([]);
const loading = ref(true);
const error = ref(null);

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/facebook-feed');
        posts.value = data.posts;
        if (data.error) error.value = data.error;
    } catch (e) {
        error.value = 'Unable to load Facebook feed right now.';
    } finally {
        loading.value = false;
    }
});

function formatDate(iso) {
    return new Date(iso).toLocaleDateString('en-GB', {
        day: 'numeric', month: 'long', year: 'numeric',
    });
}

function truncate(text, length = 180) {
    if (!text || text.length <= length) return text;
    return text.slice(0, length).trimEnd() + '…';
}
</script>

<template>
    <section id="facebook" class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="flex items-end justify-between mb-14">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-brand-600 mb-3 block">
                        Social Media
                    </span>
                    <h2 class="font-display text-3xl sm:text-4xl font-bold text-warm-900 flex items-center gap-3">
                        <!-- Facebook logo icon -->
                        <span
                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#1877F2] text-white shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </span>
                        Latest from Facebook
                    </h2>
                </div>
                <a href="https://www.facebook.com/woodvalecommunitycentre" target="_blank" rel="noopener"
                    class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-[#1877F2] hover:text-blue-700 transition-colors">
                    Follow us on Facebook
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <!-- Loading skeleton -->
            <div v-if="loading" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="n in 6" :key="n" class="rounded-2xl border border-warm-200 overflow-hidden animate-pulse">
                    <div class="h-44 bg-warm-100"></div>
                    <div class="p-5 space-y-3">
                        <div class="h-3 bg-warm-100 rounded w-1/3"></div>
                        <div class="h-4 bg-warm-100 rounded w-full"></div>
                        <div class="h-4 bg-warm-100 rounded w-4/5"></div>
                        <div class="h-4 bg-warm-100 rounded w-2/3"></div>
                    </div>
                </div>
            </div>

            <!-- Error state -->
            <div v-else-if="error && !posts.length"
                class="text-center py-16 px-4 bg-warm-50 rounded-2xl border border-warm-200">
                <span class="text-4xl block mb-3">📭</span>
                <p class="text-warm-500 text-sm">{{ error }}</p>
                <a href="https://www.facebook.com" target="_blank" rel="noopener"
                    class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-[#1877F2] text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                    Visit our Facebook page
                </a>
            </div>

            <!-- Posts grid -->
            <div v-else class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <a v-for="post in posts" :key="post.id" :href="post.permalink_url" target="_blank" rel="noopener"
                    class="group flex flex-col rounded-2xl border border-warm-200 overflow-hidden hover:border-blue-300 hover:shadow-md transition-all">

                    <!-- Post image -->
                    <div v-if="post.picture" class="overflow-hidden bg-warm-100 shrink-0">
                        <img :src="post.picture" :alt="post.message"
                            class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300" />
                    </div>
                    <!-- No image placeholder -->
                    <div v-else class="h-16 bg-gradient-to-r from-[#1877F2] to-blue-400 shrink-0"></div>

                    <div class="flex flex-col flex-1 p-5">
                        <!-- Date + FB icon -->
                        <div class="flex items-center justify-between mb-3">
                            <time class="text-xs text-warm-400 font-medium">
                                {{ formatDate(post.created_time) }}
                            </time>
                            <svg class="w-4 h-4 text-[#1877F2] opacity-60" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </div>

                        <!-- Message -->
                        <p class="text-sm text-warm-700 leading-relaxed flex-1">
                            {{ truncate(post.message) }}
                        </p>

                        <!-- Read more -->
                        <div class="mt-4 flex items-center gap-1 text-xs font-medium text-[#1877F2]">
                            Read on Facebook
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Follow CTA (mobile) -->
            <div class="sm:hidden mt-8 text-center">
                <a href="https://www.facebook.com/woodvalecommunitycentre" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1877F2] text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                    Follow us on Facebook
                </a>
            </div>

        </div>
    </section>
</template>
