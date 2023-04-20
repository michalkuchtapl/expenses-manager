<script setup>
    import {computed, onMounted, ref} from 'vue';
    import {useForm, usePage} from "@inertiajs/vue3";
    import ProgressSpinner from "primevue/progressspinner";
    import Dialog from "primevue/dialog";
    import InputNumber from "primevue/inputnumber";
    import Button from "primevue/button";
    import Calendar from "primevue/calendar";
    import Checkbox from "primevue/checkbox";

    const loading = ref(false);
    const loaded = ref(false);
    const year = ref((new Date).getFullYear());
    const data = ref([]);

    const expenseTypes = computed(() => usePage().props.enums.expenses.types);

    const fetchData = () => {
        loading.value = true;
        fetch(route('expense.payments.list') + "?year=" + year.value)
            .then(response => response.json())
            .then(({expenses}) => {
                data.value = expenses;
                loading.value = false;
                loaded.value = true;
            });
    }

    onMounted(() => {
        fetchData();
    });

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

    const getExpenseType = (expense) => {
        return expenseTypes.value.find((type) => {
            return type.value == expense.type;
        });
    }

    const prepareTooltip = (row, payment) => {
        const expenseType = getExpenseType(row);

        if (!payment.due_date) {
            return `Not Applicable this Month`;
        }

        return `<span class="font-bold">${row.name}</span>
            <span class="font-bold">Value:</span> ${payment.value?.toFixed(2)} zł
            <span class="font-bold">Due Date:</span> ${payment.due_date}
            <span class="font-bold">Type:</span> ${expenseType.label}`;
    };

    const opened = ref(false);
    const editing = ref(false);
    const form = useForm({
        value: 0,
        dueDate: null,
        updateFuture: false,
        updatePast: false,
    });
    const openedPayment = ref();
    const openedExpense = ref();

    const openModal = (expense, payment) => {
        openedExpense.value = expense;
        openedPayment.value = payment;
        form.value = payment.value;
        form.dueDate = payment.due_date;
        opened.value = true;
    };

    const closeModal = () => {
        opened.value = false;
        editing.value = false;
        openedPayment.value = null;
        openedExpense.value = null;
        form.reset()
    };

    const resetForm = () => {
        form.reset();
        form.value = openedPayment.value.value;
        form.dueDate = openedPayment.value.due_date;
    };

    const toggleForm = () => {
        if (editing.value) {
            editing.value = false;
            resetForm();
        } else {
            editing.value = true;
        }
    };
</script>

<template>
    <div
        class="w-full max-h-30rem"
        :class="{
            'text-center': loading || !loaded,
            'overflow-hidden': loading || !loaded,
            'overflow-scroll': !loading && loaded,
        }"
    >
        <ProgressSpinner v-if="loading || !loaded" class="auto" />
        <template v-if="!loading && loaded">
            <Dialog modal v-model:visible="opened" class="w-full sm:w-half-screen" :header="openedExpense?.name" @hide="closeModal">
                <div v-if="editing">
                    <div class="mb-2">
                        <label class="font-bold block mb-2">Value</label>
                        <InputNumber v-model="form.value" :class="{ 'p-invalid': form.errors.value }" prefix="PLN " class="w-full" />
                        <small class="p-error" v-if="form.errors.value">{{ form.errors.value }}</small>
                    </div>
                    <div class="mb-2">
                        <label class="font-bold block mb-2">Due Date</label>
                        <Calendar v-model="form.dueDate" :class="{ 'p-invalid': form.errors.dueDate }" showIcon class="w-full"  />
                        <small class="p-error" v-if="form.errors.dueDate">{{ form.errors.dueDate }}</small>
                    </div>
                    <div class="mb-2 flex align-items-center">
                        <Checkbox v-model="form.updateFuture" />
                        <label class="font-bold block ml-2">Update future payments</label>
                    </div>
                    <div class="mb-2 flex align-items-center">
                        <Checkbox v-model="form.updatePast" />
                        <label class="font-bold block ml-2">Update past payments</label>
                    </div>
                </div>
                <div v-if="!editing">
                    <span class="font-bold">Value:</span> {{(openedPayment.value ?? 0).toFixed(2)}} PLN <br>
                    <span class="font-bold">Due Date:</span> {{openedPayment.dueDate}} <br>
                    <span class="font-bold">Type:</span> {{getExpenseType(openedExpense).label}}
                </div>

                <template #footer>
                    <Button v-if="openedPayment.enabled" severity="warning">Skip this payment</Button>
                    <Button v-if="!openedPayment.enabled" severity="info">Renew this payment</Button>
                    <Button v-if="!editing" severity="secondary" @click="toggleForm">Edit</Button>
                    <Button v-if="editing" severity="secondary" @click="toggleForm">Cancel</Button>
                    <Button v-if="editing" severity="success">Save</Button>
                </template>
            </Dialog>

            <table class="w-full border-1">
                <colgroup>
                    <col>
                    <col>
                    <col v-for="(column, index) in months" :key="index" style="width: 100px;min-width: 100px;max-width: 100px">
                </colgroup>
                <thead>
                <tr>
                    <th style="padding: 5px">Name</th>
                    <th style="padding: 5px">Value</th>
                    <th v-for="(column, index) in months" :key="index" style="padding: 5px">{{ column.label }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(row, index) in data" :key="index">
                    <td style="padding: 5px">{{ row.name }}</td>
                    <td style="padding: 5px">{{ row.value }}</td>
                    <td
                        v-for="(payment, paymentIndex) in row.payments"
                        :key="paymentIndex"
                        style="padding: 5px"
                        class="cursor-pointer"
                        @click="openModal(row, payment)"
                        v-tooltip.top="{
                            value: prepareTooltip(row, payment),
                            escape: true
                        }"
                        :class="{
                            'surface-300': !payment.applicable,
                            'bg-white': payment.applicable && !payment.paid,
                            'bg-green-400': payment.applicable && payment.paid,
                            'bg-red-400': payment.applicable && !payment.paid && payment.pastDueDate,
                        }"
                    >
                    </td>
                </tr>
                </tbody>
            </table>
        </template>
    </div>
</template>
