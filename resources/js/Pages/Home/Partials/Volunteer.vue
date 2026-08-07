<script setup>
import { Link } from '@inertiajs/vue3';
import PrimaryLink from '@/Components/PrimaryLink.vue';
</script>

<template>
    <section id="volunteer" class="py-20 bg-gradient-to-r from-brand-600 to-brand-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-6 left-12 text-8xl">🌳</div>
            <div class="absolute bottom-6 right-16 text-8xl">🤲</div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-9xl opacity-20">💚</div>
        </div>
        <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-white mb-5">
                Want to make a difference?
            </h2>
            <p class="text-white/80 text-lg max-w-2xl mx-auto mb-10">
                Whether you'd like to volunteer, donate, or simply come along and see what we're about —
                we'd love to hear from you. No experience necessary. Just bring a smile.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <Link href="#contact"
                    class="px-7 py-3.5 bg-white text-brand-700 font-semibold rounded-xl hover:bg-brand-50 transition-colors shadow-lg text-sm">
                    Become a Volunteer
                </Link>
                <PrimaryLink href="#contact">Make a Donation</PrimaryLink>
                <a href="https://www.amazon.co.uk/hz/wishlist/ls/1N50BMHQRLYJH?ref_=wl_share"
                    class="px-7 py-3.5 bg-white text-brand-700 font-semibold rounded-xl hover:bg-brand-50 transition-colors shadow-lg text-sm">
                    Amazon Wishlist
                </a>
            </div>
        </div>
    </section>
</template>
