<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    classes: {
        type: String
    },
    href: {
        type: String
    },
});
</script>

<template>
    <Link v-if="type === 'link'" :href="href" :class="classes">
        <slot />
    </Link>

    <button v-else :type="type" :class="classes">
        <slot />
    </button>
</template>
