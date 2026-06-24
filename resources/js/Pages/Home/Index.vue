<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import FacebookFeed from "@/Components/FacebookFeed.vue";
import Hero from "@/Pages/Home/Partials/Hero.vue";
import Stats from "@/Pages/Home/Partials/Stats.vue";
import Testimonials from "@/Pages/Home/Partials/Testimonials.vue";
import About from "@/Pages/Home/Partials/About.vue";
import Services from "@/Pages/Home/Partials/Services.vue";
import Events from "@/Pages/Home/Partials/Events.vue";
import News from "@/Pages/Home/Partials/News.vue";
import Volunteer from "@/Pages/Home/Partials/Volunteer.vue";
import Contact from "@/Pages/Home/Partials/Contact.vue";
import Header from "@/Layouts/Partials/Header.vue";
import Footer from "@/Layouts/Partials/Footer.vue";

const page = usePage();

defineProps({
  site: { type: Object },
  stats: { type: Array, default: () => [] },
  services: { type: Array, default: () => [] },
  events: { type: Array, default: () => [] },
  testimonials: { type: Array, default: () => [] },
  featuredTestimonial: { type: Object, default: () => null },
});

const navLinks = page.props.site.nav_links;
</script>

<template>
  <Head :title="site.fullname" />

  <div class="font-sans antialiased text-warm-800 bg-white">
    <Header :navLinks="navLinks" />

    <!-- ── HERO ── -->
    <Hero :site="site" />

    <!-- ── STATS ── -->
    <Stats :stats="stats" />

    <!-- ── ABOUT ── -->
    <About :testimonial="featuredTestimonial" />

    <!-- ── SERVICES ── -->
    <Services :services="services" />

    <!-- ── EVENTS ── -->
    <Events :events="events" />

    <!-- ── NEWS ── -->
    <News />

    <!-- ── TESTIMONIALS ── -->
    <Testimonials :testimonials="testimonials" />

    <!-- ── FACEBOOK FEED ── -->
    <FacebookFeed />

    <!-- ── VOLUNTEER CTA ── -->
    <Volunteer />

    <!-- ── CONTACT ── -->
    <Contact :site="site" />

    <!-- ── FOOTER ── -->
    <Footer :navLinks="navLinks" />
  </div>
</template>
