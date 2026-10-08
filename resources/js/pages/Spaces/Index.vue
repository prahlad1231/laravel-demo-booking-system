<script setup lang="ts">
import {Head, Link} from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import type {Space} from "@/types";

defineProps<{ spaces: Space[] }>();
</script>

<template>
    <Head title="Spaces" />

    <AppLayout>
        <h1 class="mb-6 text-2xl font-semibold">Spaces</h1>

        <ul class="divide-y divide-gray-200 rounded-lg border border-gray-200 bg-white">
            <li v-for="space in spaces" :key="space.id" class="flex items-center justify-between px-4 py-3">
                <div>
                    <p class="font-medium">{{ space.name }}</p>
                    <p class="text-sm text-gray-500">Capacity: {{ space.capacity }}</p>
                </div>
            </li>
        </ul>
    </AppLayout>
</template>

<style scoped>

</style>
