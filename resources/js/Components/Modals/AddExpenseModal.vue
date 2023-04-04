<script setup>
import {ref} from 'vue'
import {useForm, router} from "@inertiajs/vue3";
import InputNumber from "primevue/inputnumber";
import InputText from "primevue/inputtext";
import Calendar from "primevue/calendar";
import SelectButton from "primevue/selectbutton";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import MultiSelect from "primevue/multiselect";

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
            router.visit(route('dashboard'), {
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
    <Button
        severity="success"
        size="small"
        class="p-button-sm absolute"
        @click="showModal"
        icon="pi pi-plus"
        aria-label="Add Expense"
        style="top: 7px; right: 10px"
    />
    <Dialog v-model:visible="isShowModal" class="md:w-10 lg:w-3 w-full" :style="{ width: '50vw' }" modal header="Add new expense for current month">
        <div class="mb-2">
            <label class="font-bold block mb-2">Name</label>
            <InputText v-model="form.name" class="w-full" />
        </div>
        <div class="mb-2">
            <label class="font-bold block mb-2">Value</label>
            <InputNumber v-model="form.value" prefix="PLN " class="w-full" />
        </div>
        <div class="mb-2">
            <label class="font-bold block mb-2">Expense Type</label>
            <SelectButton v-model="form.type" :options="expenseTypes" optionLabel="label" optionValue="value" />
        </div>
        <div class="mb-2">
            <label class="font-bold block mb-2">Months</label>
            <MultiSelect
                v-model="form.months"
                :options="months"
                display="chip"
                optionLabel="label"
                optionValue="value"
                placeholder="Select Months"
                class="w-full"
            />
        </div>
        <div class="mb-2">
            <label class="font-bold block mb-2">Start at</label>
            <Calendar v-model="form.start_date" showIcon class="w-full"  />
        </div>
        <div class="mb-2"  v-if="form.type != 'one_time'">
            <label class="font-bold block mb-2">Finish at</label>
            <Calendar v-model="form.end_date" showIcon class="w-full"  />
        </div>

        <template #footer>
            <div class="flex justify-content-between">
                <Button severity="danger" :loading="isLoading" @click="closeModal" label="Close" />
                <Button severity="success" :loading="isLoading" @click="save" label="Save" />
            </div>
        </template>
    </Dialog>
</template>
