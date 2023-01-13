<script setup>
import {Button, Input, Modal} from 'flowbite-vue'
import { ref } from 'vue'

defineProps({

});

const isShowModal = ref(false)
function save() {
    isShowModal.value = false
}
function closeModal() {
    isShowModal.value = false
}
function showModal() {
    isShowModal.value = true
}

const form = {
    name: '',
    value: ''
};

</script>

<template>
    <button @click="showModal" class="bg-green-500 text-black px-3 py-1 mb-1 rounded-lg" title="Add Expense">
        +
    </button>
    <Modal size="xl" v-if="isShowModal" @close="closeModal">
        <template #header>
            <div class="flex items-center text-lg">
                Add new expense for current month
            </div>
        </template>
        <template #body>
            <Input label="Name" v-model="form.name" />
            <Input label="Value" v-model="form.value" type="number" />
        </template>
        <template #footer>
            <div class="flex justify-between">
                <Button color="red" @click="closeModal">Close</Button>
                <Button color="green" @click="save">Save</Button>
            </div>
        </template>
    </Modal>
</template>
