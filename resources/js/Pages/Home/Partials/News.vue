<script setup>
import { Link } from "@inertiajs/vue3";

const news = [];
</script>

<template>
    <section id="news" class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-14">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-brand-600 mb-3 block">Latest
                        News</span>
                    <h2 class="font-display text-3xl sm:text-4xl font-bold text-warm-900">
                        From the centre
                    </h2>
                </div>
                <Link href="/news-updates"
                    class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-brand-600 hover:text-brand-800 transition-colors">
                    All news
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>

            <div class="grid md:grid-cols-3 gap-7">
                <article v-for="item in news" :key="item.title"
                    class="group border border-warm-200 rounded-2xl p-6 hover:border-brand-300 hover:shadow-md transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold" :class="item.tagColor">{{ item.tag
                            }}</span>
                        <time class="text-xs text-warm-400">{{ item.date }}</time>
                    </div>
                    <h3
                        class="font-semibold text-warm-900 mb-3 leading-snug group-hover:text-brand-700 transition-colors">
                        {{ item.title }}
                    </h3>
                    <p class="text-sm text-warm-500 leading-relaxed">{{ item.excerpt }}</p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-medium text-brand-600">
                        Read more
                        <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
