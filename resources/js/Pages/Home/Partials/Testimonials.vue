<script setup>
defineProps({
    testimonials: { type: Array, default: () => [] },
});
</script>

<template>
    <section id="testimonials" class="py-20 bg-warm-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-xs font-semibold uppercase tracking-widest text-brand-600 mb-3 block">Testimonials</span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-warm-900">
                    What our community says
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <figure v-for="testimonial in testimonials" :key="testimonial.id" class="flex flex-col p-7 rounded-2xl bg-white border border-warm-200 shadow-sm">
                    <div class="text-4xl text-brand-200 font-display leading-none mb-3">“</div>
                    <h3 class="font-semibold text-warm-900 text-base mb-2">{{ testimonial.title }}</h3>
                    <blockquote class="text-sm text-warm-600 leading-relaxed flex-1">{{ testimonial.comment }}</blockquote>
                    <figcaption class="mt-5 pt-4 border-t border-warm-100 text-sm font-semibold text-brand-700">
                        — {{ testimonial.name }}
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>
</template>
