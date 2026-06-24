<script setup>
import PrimaryLink from '@/Components/PrimaryLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

defineProps({
    testimonial: { type: Object, default: () => null },
});
</script>

<template>
    <section id="about" class="py-20 bg-warm-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-brand-600 mb-3 block">About
                        Us</span>
                    <h2 class="font-display text-3xl sm:text-4xl font-bold text-warm-900 mb-6 leading-tight">
                        Built by the community,<br />for the community
                    </h2>
                    <p class="text-warm-600 leading-relaxed mb-4">
                        Woodvale &amp; Ainsdale Community Centre is a registered charity dedicated to
                        improving lives and strengthening the bonds that make our neighbourhood special.
                        We believe everyone deserves a place to belong.
                    </p>
                    <p class="text-warm-600 leading-relaxed mb-8">
                        From our daily café and social groups to our youth programmes and support services,
                        everything we do is driven by the needs of local people — shaped by volunteers,
                        funded by donations, and delivered with care.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <PrimaryLink href="/services">What We Offer</PrimaryLink>
                        <Link href="/contact"
                            class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-brand-200 text-brand-700 text-sm font-semibold rounded-xl hover:border-brand-400 transition-colors">
                            Contact Us
                        </Link>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div v-if="testimonial" class="bg-brand-600 rounded-2xl p-6 text-white col-span-2">
                        <p class="text-2xl font-display font-bold mb-2">"{{ testimonial.title }}"</p>
                        <p class="text-white/80 text-sm leading-relaxed">
                            "{{ testimonial.comment }}"
                        </p>
                        <p class="text-white/60 text-xs mt-3 font-medium">— {{ testimonial.name }}</p>
                    </div>
                    <div v-else class="bg-brand-600 rounded-2xl p-6 text-white col-span-2">
                        <p class="text-2xl font-display font-bold mb-2">"A true community gem"</p>
                        <p class="text-white/80 text-sm leading-relaxed">
                            "I've been coming to the centre for 15 years. It's changed my life — I made friends here,
                            got help when I needed it, and now I volunteer every Thursday."
                        </p>
                        <p class="text-white/60 text-xs mt-3 font-medium">— Margaret T., Ainsdale resident</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 border border-warm-200 flex flex-col items-start gap-2">
                        <span class="text-3xl">🏅</span>
                        <p class="text-sm font-semibold text-warm-800">Registered Charity</p>
                        <p class="text-xs text-warm-500">No. {{ page.props.site.charity_number }}</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 border border-warm-200 flex flex-col items-start gap-2">
                        <span class="text-3xl">🌿</span>
                        <p class="text-sm font-semibold text-warm-800">Est. {{ page.props.site.established }}</p>
                        <p class="text-xs text-warm-500">{{ 2026 - page.props.site.established }} years of service</p>
                    </div>
                </div>
            </div>

            <div class="w-full max-w-3xl mx-auto py-12 pt-24" tabindex="0" role="application" :aria-label="page.props.site.full_name + ' video'">
                <div class="border border-warm-200 rounded-2xl overflow-hidden">
                    <video class="w-full" preload="metadata" controls
                        src="/media/VID-20181207-WA0007.mp4">
                    </video>
                </div>
            </div>
        </div>
    </section>
</template>