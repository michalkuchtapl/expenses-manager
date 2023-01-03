<script setup>
import BaseButton from "@/Components/BaseButton.vue";

defineProps({
    type: {
        type: String,
        default: 'button',
    },
    classes: {
        type: String,
        default: 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition'
    },
    href: {
        type: String
    },
});
</script>

<template>
    <BaseButton :type="type" :href="href" :class="classes">
        <slot />
    </BaseButton>
</template>
