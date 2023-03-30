<script setup>
import {Alert, Button, Input, Modal} from 'flowbite-vue'
import {computed, reactive, ref, watchEffect} from 'vue'
import Select from "@/Components/Flowbite/Select.vue";
import Checkboxes from "@/Components/Flowbite/Checkboxes.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import { Inertia } from '@inertiajs/inertia'
import DatePicker from "@/Components/Flowbite/DatePicker.vue";

const isLoading = ref(false);
const isShowModal = ref(false);
const errorMessage = ref('');

const form = useForm({
    name: '',
    value: 0,
    type: 'one_time',
    months: [],
    start_date: null,
    end_date: null,
});

const expenseTypes = [
    {value: 'one_time', label: 'One Time'},
    {value: 'monthly', label: 'Each Month'},
    {value: 'selected_months', label: 'Selected Months'},
    {value: 'yearly', label: 'Each Year'},
];

const months = [
    {value: '01', label: 'January'},
    {value: '02', label: 'February'},
    {value: '03', label: 'March'},
    {value: '04', label: 'April'},
    {value: '05', label: 'May'},
    {value: '06', label: 'June'},
    {value: '07', label: 'July'},
    {value: '08', label: 'August'},
    {value: '09', label: 'September'},
    {value: '10', label: 'October'},
    {value: '11', label: 'November'},
    {value: '12', label: 'December'},
];


function save() {
    isLoading.value = true;

    if (form.type == 'one_time')
        form.end_date = form.start_date;

    axios.post(route('expense.store'), form.data())
        .then((response) => {
            form.reset();
            isShowModal.value = false
            isLoading.value = false;
            Inertia.visit(route('dashboard'), {
                only: ['totalExpense']
            })
        })
        .catch((error) => {
            errorMessage.value = error.response.data.message;
            isLoading.value = false;
        });
}
function closeModal() {
    form.reset();
    isLoading.value = false;
    isShowModal.value = false;
}
function showModal() {
    form.reset();
    isLoading.value = false;
    isShowModal.value = true;
}

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
            <Alert v-if="errorMessage && errorMessage.length > 0" type="danger" class="mb-2">
                {{ errorMessage }}
            </Alert>

            <Input label="Name" v-model="form.name" class="mb-2" required />
            <Input label="Value" v-model="form.value" type="number" class="mb-2" required min="1" />
            <Select :options="expenseTypes" label="Expense Type" v-model="form.type" class="mb-2" required />
            <Checkboxes v-if="form.type == 'selected_months'" label="Months" :options="months" v-model="form.months" class="mb-2" required />

            <DatePicker label="Start at" v-model="form.start_date" />
            <DatePicker v-if="form.type != 'one_time'" label="Finish at" v-model="form.end_date" />
        </template>
        <template #footer>
            <div class="flex justify-between">
                <Button :loading="isLoading" color="red" @click="closeModal">Close</Button>
                <Button :loading="isLoading" color="green" @click="save">Save</Button>
            </div>
        </template>
    </Modal>
</template>
