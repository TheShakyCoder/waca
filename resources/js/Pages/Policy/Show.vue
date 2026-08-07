<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import Header from '@/Layouts/Partials/Header.vue';
import Footer from '@/Layouts/Partials/Footer.vue';

const page = usePage();

const props = defineProps({
    title: { type: String, required: true },
    intro: { type: String, default: '' },
    sections: { type: Array, default: () => [] },
});

const navLinks = page.props.site.nav_links;
</script>

<template>

    <Head :title="`${title} — ${page.props.site.fullname}`" />

    <div class="font-sans antialiased text-warm-800 bg-white">
        <Header :navLinks="navLinks" />

        <main class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-warm-200/80 bg-warm-50/60 p-8 sm:p-10 shadow-sm">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-warm-600">Policies</p>
                <h1 class="mt-3 font-display text-4xl text-warm-900">{{ title }}</h1>
                <p v-if="intro" class="mt-4 text-lg leading-relaxed text-warm-700">{{ intro }}</p>
                <p class="mt-4 text-sm text-warm-600">
                    Last updated: {{ new Date().toLocaleDateString('en-GB', {
                        day: 'numeric', month: 'long', year:
                    'numeric' }) }}
                </p>
            </div>

            <div class="mt-10 space-y-8 text-base leading-8 text-warm-700">
                <section v-for="section in sections" :key="section.heading">
                    <h2 class="text-xl font-semibold text-warm-900">{{ section.heading }}</h2>
                    <p v-if="section.body" class="mt-3">{{ section.body }}</p>
                    <ul v-if="section.list" class="mt-3 space-y-2">
                        <li v-for="item in section.list" :key="item" class="flex gap-2">
                            <span class="mt-2 h-1.5 w-1.5 rounded-full bg-warm-500"></span>
                            <span>{{ item }}</span>
                        </li>
                    </ul>
                </section>
            </div>
        </main>

        <Footer :navLinks="navLinks" />
    </div>
</template>
