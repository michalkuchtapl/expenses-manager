<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'inline-flex align-items-center px-1 pt-1 border-bottom-2 border-indigo-400 text-sm font-medium text-gray-900 focus:border-indigo-700 transition-all'
        : 'inline-flex align-items-center px-1 pt-1 border-bottom-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300 transition-all';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
