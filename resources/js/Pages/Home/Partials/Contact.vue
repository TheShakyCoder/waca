<script setup>
defineProps({
    site: Object
})
</script>

<template>
    <section id="contact" class="py-20 bg-warm-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-xs font-semibold uppercase tracking-widest text-brand-600 mb-3 block">Get In
                    Touch</span>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-warm-900">We'd love to hear from you</h2>
                <p class="text-warm-500 mt-4 max-w-xl mx-auto">Drop in, give us a call, or send us a message. We're here
                    Mon–Sat.</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact info -->
                <div class="space-y-6">
                    <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-warm-200">
                        <div
                            class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center text-lg shrink-0">
                            📍</div>
                        <div>
                            <p class="font-semibold text-warm-900 text-sm">Address</p>
                            <p class="text-warm-500 text-sm mt-0.5" v-html="site.fullname + '<br>' + site.address"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-warm-200">
                        <div
                            class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center text-lg shrink-0">
                            📞</div>
                        <div>
                            <p class="font-semibold text-warm-900 text-sm">Phone</p>
                            <p class="text-warm-500 text-sm mt-0.5">{{ site.telephone }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-warm-200">
                        <div
                            class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center text-lg shrink-0">
                            ✉️</div>
                        <div>
                            <p class="font-semibold text-warm-900 text-sm">Email</p>
                            <a :href="`mailto:${site.email}`" class="text-warm-500 text-sm mt-0.5">{{ site.email
                            }}</a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-warm-200">
                        <div
                            class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center text-lg shrink-0">
                            🕐</div>
                        <div>
                            <p class="font-semibold text-warm-900 text-sm">Opening Hours</p>
                            <p class="text-warm-500 text-sm mt-0.5" v-html="site.opening_times"></p>
                        </div>
                    </div>
                </div>

                <!-- Contact form -->
                <form class="bg-white rounded-2xl border border-warm-200 p-7 space-y-5 shadow-sm">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-warm-700 mb-1.5">First name</label>
                            <input type="text" placeholder="Jane"
                                class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-warm-700 mb-1.5">Last name</label>
                            <input type="text" placeholder="Smith"
                                class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-warm-700 mb-1.5">Email</label>
                        <input type="email" placeholder="jane@example.com"
                            class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-warm-700 mb-1.5">Subject</label>
                        <select
                            class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition text-warm-600">
                            <option>General enquiry</option>
                            <option>Volunteering</option>
                            <option>Venue hire</option>
                            <option>Donation</option>
                            <option>Events</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-warm-700 mb-1.5">Message</label>
                        <textarea rows="4" placeholder="How can we help?"
                            class="w-full px-4 py-2.5 text-sm border border-warm-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent transition resize-none"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full py-3 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition-colors text-sm shadow-sm">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>
