<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() => {
    return props.active
        ? 'border-indigo-400 text-indigo-700 bg-indigo-50 focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700'
        : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300';
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" :class="classes" class="w-full text-left block pl-3 pr-4 py-2 border-left-3 text-base font-medium transition-all">
            <slot />
        </button>

        <Link v-else :href="href" :class="classes" class="w-full text-left block pl-3 pr-4 py-2 border-left-3 text-base font-medium transition-all">
            <slot />
        </Link>
    </div>
</template>
