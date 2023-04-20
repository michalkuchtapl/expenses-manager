<script setup>
import {computed, ref} from 'vue'
import {useForm, router, usePage} from "@inertiajs/vue3";
import InputNumber from "primevue/inputnumber";
import InputText from "primevue/inputtext";
import Calendar from "primevue/calendar";
import SelectButton from "primevue/selectbutton";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import MultiSelect from "primevue/multiselect";
import Dropdown from "primevue/dropdown";

const isLoading = ref(false);
const isShowModal = ref(false);

const form = useForm({
    name: '',
    value: 0,
    type: 'one_time',
    months: [],
    start_date: (new Date()),
    end_date: null,
    category: 'other',
    payment_type: 'credit_card',
});

const categories = computed(() => usePage().props.enums.expenses.categories);
const paymentTypes = computed(() => usePage().props.enums.expenses.payment_types);
const expenseTypes = computed(() => usePage().props.enums.expenses.types);

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
    if (form.type == 'one_time')
        form.end_date = form.start_date;

    form.post(route('expense.store'), {
        onSuccess: () => closeModal()
    });
}
function closeModal() {
    form.reset();
    isShowModal.value = false;
}
function showModal() {
    form.reset();
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
            <InputText v-model="form.name" :class="{ 'p-invalid': form.errors.name }" class="w-full" />
            <small class="p-error" v-if="form.errors.name">{{ form.errors.name }}</small>
        </div>
        <div class="mb-2">
            <label class="font-bold block mb-2">Value</label>
            <InputNumber v-model="form.value" :class="{ 'p-invalid': form.errors.value }" prefix="PLN " class="w-full" />
            <small class="p-error" v-if="form.errors.value">{{ form.errors.value }}</small>
        </div>
        <div class="mb-2">
            <label class="font-bold block mb-2">Category</label>
            <Dropdown v-model="form.category" :class="{ 'p-invalid': form.errors.category }" option-label="label" option-value="value" :options="categories" class="w-full" />
            <small class="p-error" v-if="form.errors.category">{{ form.errors.category }}</small>
        </div>
        <div class="mb-2">
            <label class="font-bold block mb-2">Payment Type</label>
            <Dropdown v-model="form.payment_type" :class="{ 'p-invalid': form.errors.payment_type }" option-label="label" option-value="value" :options="paymentTypes" class="w-full" />
            <small class="p-error" v-if="form.errors.payment_type">{{ form.errors.payment_type }}</small>
        </div>
        <div class="mb-2">
            <label class="font-bold block mb-2">Expense Type</label>
            <SelectButton v-model="form.type" :class="{ 'p-invalid': form.errors.type }" :options="expenseTypes" optionLabel="label" optionValue="value" />
            <small class="p-error" v-if="form.errors.type">{{ form.errors.type }}</small>
        </div>
        <div class="mb-2" v-if="form.type == 'selected_months'">
            <label class="font-bold block mb-2">Months</label>
            <MultiSelect
                v-model="form.months"
                :options="months"
                display="chip"
                optionLabel="label"
                optionValue="value"
                placeholder="Select Months"
                class="w-full"
                :class="{ 'p-invalid': form.errors.months }"
            />
            <small class="p-error" v-if="form.errors.months">{{ form.errors.months }}</small>
        </div>
        <div class="mb-2">
            <label class="font-bold block mb-2">Start at</label>
            <Calendar v-model="form.start_date" :class="{ 'p-invalid': form.errors.start_date }" showIcon class="w-full"  />
            <small class="p-error" v-if="form.errors.start_date">{{ form.errors.start_date }}</small>
        </div>
        <div class="mb-2"  v-if="form.type != 'one_time'">
            <label class="font-bold block mb-2">Finish at</label>
            <Calendar v-model="form.end_date" :class="{ 'p-invalid': form.errors.end_date }" showIcon class="w-full"  />
            <small class="p-error" v-if="form.errors.end_date">{{ form.errors.end_date }}</small>
        </div>

        <template #footer>
            <div class="flex justify-content-between">
                <Button severity="danger" :loading="form.processing" @click="closeModal" label="Close" />
                <Button severity="success" :loading="form.processing" @click="save" label="Save" />
            </div>
        </template>
    </Dialog>
</template>
