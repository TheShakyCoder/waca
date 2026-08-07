<script setup>
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

defineProps({
    navLinks: { type: Array },
});

const page = usePage();
</script>

<template>
    <footer class="bg-warm-900 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid md:grid-cols-4 gap-10 mb-10">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="/media/logo-circle-background.png" alt="WACA Logo"
                            class="h-14 w-auto invert opacity-90" />
                        <div>
                            <p class="font-semibold text-white font-display">Woodvale &amp; Ainsdale</p>
                            <p class="text-xs text-white/50">Community Association</p>
                        </div>
                    </div>
                    <p class="text-white/60 text-sm leading-relaxed max-w-xs">
                        A registered charity serving the communities of Woodvale and Ainsdale, Southport since 1993.
                    </p>
                    <p class="text-white/40 text-xs mt-3">Registered Charity No. {{ page.props.site.charity_number }}
                    </p>
                </div>

                <div>
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-4">Quick Links</p>
                    <ul class="space-y-2">
                        <li v-for="link in navLinks" :key="link.label">
                            <a :href="link.href" class="text-white/60 text-sm hover:text-white transition-colors">{{
                                link.label }}</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-4">Get Involved</p>
                    <ul class="space-y-2 text-sm text-white/60 mb-6">
                        <li>
                            <Link href="#volunteer" class="hover:text-white transition-colors">Volunteer with us</Link>
                        </li>
                        <li>
                            <Link href="#contact" class="hover:text-white transition-colors">Make a donation</Link>
                        </li>
                        <li>
                            <Link href="#contact" class="hover:text-white transition-colors">Hire our venue</Link>
                        </li>
                    </ul>
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-3">Follow Us</p>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/woodvalecommunitycentre" target="_blank"
                            rel="noopener noreferrer" aria-label="Visit Woodvale Community Centre on Facebook"
                            class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors text-sm">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M13.397 20.997v-8.196h2.759l.412-3.2h-3.171V4.549c0-.926.258-1.556 1.587-1.556h1.695V.099A22.6 22.6 0 0 0 14.201 0c-2.444 0-4.122 1.49-4.122 4.231v2.37H7.32v3.2h2.759v8.196h3.318Z" />
                            </svg>
                        </a>
                        <a href="https://www.tiktok.com/@woodvale_centre" target="_blank" rel="noopener noreferrer"
                            aria-label="Visit Woodvale Community Centre on TikTok"
                            class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors text-sm">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M16.5 1h-3.2c.1 1.2.4 2.3.9 3.3.5 1 .9 1.6 1.4 2.2.5.6 1.2 1.2 1.9 1.8.7.6 1.3 1.1 1.7 1.4v3.3c-.7-.2-1.3-.4-1.9-.6-.6-.2-1.2-.5-1.8-.8-.3-.1-.5-.3-.7-.4v5.6c0 3-2 5.4-4.8 6.2-1.2.4-2.4.5-3.7.2-1.3-.3-2.5-.9-3.4-1.8-1.3-1.2-2-3-1.9-4.8.1-1.8.8-3.5 2.2-4.6 1.4-1.2 3.3-1.8 5.1-1.7V9.5c-1.2-.2-2.3-.1-3.3.3-.9.3-1.7.8-2.3 1.5-.7.8-1.2 1.8-1.3 2.8-.1 1.1.1 2.2.7 3.2.6 1.1 1.7 1.9 2.9 2.3 1.3.5 2.7.5 3.9 0 1.2-.4 2.2-1.2 3-2.2.8-1 .9-2.3.9-3.5V1Z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div
                class="pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-white/40">
                <p>&copy; Fig Limited {{ new Date().getFullYear() }}. All rights reserved.
                </p>
                <div class="flex gap-5">
                    <a href="#" class="hover:text-white/70 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white/70 transition-colors">Terms of Use</a>
                    <a href="#" class="hover:text-white/70 transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>
</template>
