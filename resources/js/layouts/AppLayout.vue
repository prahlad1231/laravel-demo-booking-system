<script setup lang="ts">
    import {Link} from "@inertiajs/vue3";
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-black">
        <nav class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-5xl items-center gap-6 px-4 py-3">
                <span class="font-semibold">Demo Booking System</span>
                <Link href="/spaces" class="text-sm text-gray-600 hover:text-gray-900">Spaces</Link>
            </div>
        </nav>

        <main class="mx-auto max-w-5xl px-4 py-8">
            <slot />
        </main>
    </div>
</template>
