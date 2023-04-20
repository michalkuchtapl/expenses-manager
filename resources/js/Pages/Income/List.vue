<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Card from 'primevue/card';
import AddIncomeModal from "@/Components/Modals/AddIncomeModal.vue";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import {ref} from 'vue';

const props = defineProps({
    incomes: {
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
            sortField: 'created_at',
            sortOrder: 'acs',
            total: 0,
        }
    }
})

const loading = ref(false);
const editingRows = ref([]);

const refresh = (data = null) => {
    loading.value = true;
    router
        .visit(route('income.list', data ?? props.pageInfo));
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
    props.incomes.data[index-1] = newData;
    router.put(route('income.update', [newData.id]), newData);
}

</script>

<template>
    <AppLayout title="Incomes">
        <Card>
            <template #title>
                <div class="relative">
                    Incomes
                    <AddIncomeModal />
                </div>
            </template>
            <template #content>
                <DataTable
                    v-model:editingRows="editingRows"
                    size=""
                    lazy
                    :loading="loading"
                    :first="pageInfo.first"
                    :value="incomes.data"
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
                >
                    <Column field="id" header="Id" sortable></Column>
                    <Column field="name" header="Name" sortable>
                        <template #editor="{ data, field }">
                            <InputText v-model="data[field]" />
                        </template>
                    </Column>
                    <Column field="value" header="Amount" sortable>
                        <template #editor="{ data, field }">
                            <InputNumber v-model="data[field]" prefix="PLN " class="w-full" />
                        </template>
                    </Column>
                    <Column field="created_at" header="Created At" sortable></Column>
                    <Column :rowEditor="true" style="width: 10%; min-width: 8rem" bodyStyle="text-align:center"></Column>
                </DataTable>
            </template>
        </Card>
    </AppLayout>
</template>
