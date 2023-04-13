<script setup>
import Message from "primevue/message";

const props = defineProps({
    messages: Array
});
</script>

<template>
    <div v-for="(message, key) in messages" :key="key">
        <Message :severity="message.level == 'warning' ? 'warn' : message.level" @close="onClose(key)" :closable="false">{{ message.message }}</Message>
    </div>
</template>
