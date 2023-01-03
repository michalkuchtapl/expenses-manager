<script setup>
    defineProps({
        title: {
            type: String,
        },
        showAction: {
            type: Boolean,
            default: false
        },
    });
</script>

<template>
    <div class="bg-white overflow-hidden shadow-md rounded-lg p-5 relative">
        <div class="mb-3 border-b-2" v-if="(title && title.length > 0) || showAction">
            <div v-if="title && title.length > 0" class="text-gray-400 font-bold ">
                {{ title }}
            </div>

            <div class="absolute top-2 right-5">
                <slot name="action" />
            </div>
        </div>


        <slot />
    </div>
</template>
