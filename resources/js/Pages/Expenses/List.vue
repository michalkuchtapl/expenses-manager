<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import {router, usePage} from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Card from 'primevue/card';
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import {computed, ref} from 'vue';
import AddExpenseModal from "@/Components/Modals/AddExpenseModal.vue";
import Calendar from "primevue/calendar";
import Dropdown from "primevue/dropdown";

const props = defineProps({
    expenses: {
        type: Object,
        default: {
            current_page: 1,
            data: [],
            last_page: 1,
            total: 0,
        }
    },
    pageInfo: {
        type: Object,
        default: {
            first: 1,
            perPage: 15,
            sortField: 'id',
            sortOrder: 'acs',
            total: 0,
        }
    }
})

const loading = ref(false);
const editingRows = ref([]);

const categories = computed(() => usePage().props.enums.expenses.categories);
const paymentTypes = computed(() => usePage().props.enums.expenses.payment_types);

const refresh = (data = null) => {
    loading.value = true;
    router
        .visit(route('expense.list', data ?? props.pageInfo));
};

const onPage = function (event) {
    const data = {
        ...props.pageInfo,
        page: event.page+1,
        perPage: event.rows,
    };
    props.pageInfo = data;

    refresh(data);
}

const onSort = function (event) {
    const data = {
        ...props.pageInfo,
        sortField: event.sortField,
        sortOrder: event.sortOrder > 0 ? 'asc' : 'desc',
    };
    props.pageInfo = data;

    refresh(data);
}

const onRowEditSave = function (event) {
    const {newData, index} = event;
    props.expenses.data[index-1] = newData;
    router.put(route('expense.update', [newData.id]), newData);
}

</script>

<template>
    <AppLayout title="Expenses">
        <Card>
            <template #title>
                <div class="relative">
                    Expenses
                    <AddExpenseModal />
                </div>
            </template>
            <template #content>
                <DataTable
                    v-model:editingRows="editingRows"
                    size=""
                    lazy
                    :loading="loading"
                    :first="pageInfo.first"
                    :value="expenses.data"
                    :paginator="true"
                    :totalRecords="pageInfo.total"
                    :rows="pageInfo.perPage"
                    :rowsPerPageOptions="[10, 15, 30 ,50, 100, 500]"
                    @page="onPage"
                    @sort="onSort"
                    :sortField="pageInfo.sortField"
                    :sortOrder="pageInfo.sortOrder == 'asc' ? 1 : -1"
                    editMode="row"
                    @row-edit-save="onRowEditSave"
                    tableClass="editable-cells-table"
                    scrollable
                    class="p-datatable-sm"
                >
                    <Column field="name" header="Name" sortable frozen style="min-width:200px; z-index:1">
                        <template #editor="{ data, field }">
                            <InputText v-model="data[field]" size="small" />
                        </template>
                    </Column>
                    <Column field="id" header="Id" sortable style="min-width:200px"></Column>
                    <Column field="value" header="Amount" sortable style="min-width:200px">
                        <template #body="slotProps">
                            {{ slotProps.data.value.toFixed(2) }} PLN
                        </template>
                        <template #editor="{ data, field }">
                            <InputNumber v-model="data[field]" prefix="PLN " class="w-full" size="small" />
                        </template>
                    </Column>
                    <Column field="payment_type" header="Payment Type" sortable style="min-width:200px">
                        <template #body="slotProps">
                            {{ paymentTypes.find((row) => row.value == slotProps.data.payment_type).label }}
                        </template>
                        <template #editor="{ data, field }">
                            <Dropdown v-model="data[field]" option-label="label" option-value="value" :options="paymentTypes" class="w-full" />
                        </template>
                    </Column>
                    <Column field="category" header="Category" sortable style="min-width:200px">
                        <template #body="slotProps">
                            {{ categories.find((row) => row.value == slotProps.data.category).label }}
                        </template>
                        <template #editor="{ data, field }">
                            <Dropdown v-model="data[field]" option-label="label" option-value="value" :options="categories" class="w-full" />
                        </template>
                    </Column>
                    <Column field="start_date" header="Start At" sortable style="min-width:200px">
                        <template #editor="{data, field}">
                            <Calendar v-model="data[field]" showIcon class="w-full"  size="small" />
                        </template>
                    </Column>
                    <Column field="end_date" header="End At" sortable style="min-width:200px">
                        <template #editor="{data, field}">
                            <Calendar v-model="data[field]" showIcon class="w-full" size="small"  />
                        </template>
                    </Column>
                    <Column :rowEditor="true" style="width: 10%; min-width: 8rem" bodyStyle="text-align:center">
                        <Button icon="pi pi-times" severity="danger" rounded outlined aria-label="Delete" size="small" />
                    </Column>
                </DataTable>
            </template>
        </Card>
    </AppLayout>
</template>
