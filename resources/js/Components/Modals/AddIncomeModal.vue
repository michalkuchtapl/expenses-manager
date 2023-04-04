<script setup>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import { ref } from 'vue';
import {useForm} from "@inertiajs/vue3";

defineProps({

});
const isLoading = ref(false);
const isShowModal = ref(false)
function save() {
    isLoading.value = true;
    form.post(route('income.store'));
    isShowModal.value = false
}
function closeModal() {
    isShowModal.value = false
}
function showModal() {
    isShowModal.value = true
}

const form = useForm({
    name: '',
    value: ''
});

</script>

<template>
    <Button
        severity="success"
        size="small"
        class="p-button-sm absolute"
        @click="showModal"
        icon="pi pi-plus"
        aria-label="Add Income"
        style="top: 7px; right: 10px"
    />
    <Dialog v-model:visible="isShowModal" class="md:w-10 lg:w-3 w-full" :style="{ width: '50vw' }" modal header="Add new income for current month">
        <div class="mb-2">
            <label class="font-bold block mb-2">Name</label>
            <InputText v-model="form.name" class="w-full" />
        </div>
        <div class="mb-2">
            <label class="font-bold block mb-2">Value</label>
            <InputNumber v-model="form.value" prefix="PLN " class="w-full" />
        </div>
        <template #footer>
            <div class="flex justify-content-between">
                <Button severity="danger" :loading="isLoading" @click="closeModal" label="Close" />
                <Button severity="success" :loading="isLoading" @click="save" label="Save" />
            </div>
        </template>
    </Dialog>
</template>
