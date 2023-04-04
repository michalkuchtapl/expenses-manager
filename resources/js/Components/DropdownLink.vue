<script setup>
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';

defineProps({
    href: String,
    as: String,
});
</script>

<template>
    <div>
        <Button v-if="as == 'button'" type="submit" severity="secondary">
            <slot />
        </Button>

        <a v-else-if="as =='a'" :href="href" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition-all">
            <slot />
        </a>

        <Link v-else :href="href" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition-all">
            <slot />
        </Link>
    </div>
</template>
