<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import Panel from "primevue/panel";
import IncomeExpanseChart from "@/Components/IncomeExpanseChart.vue";
import Button from "primevue/button";
import AddIncomeModal from "@/Components/Modals/AddIncomeModal.vue";
import AddExpenseModal from "@/Components/Modals/AddExpenseModal.vue";
import { parseDateString, isDateStringInPast, debug } from "@/helpers";
import { router } from '@inertiajs/vue3';

const props = defineProps({
    totalIncome: {
        type: Number,
        default: 0
    },

    totalExpense: {
        type: Number,
        default: 0
    },

    upcomingExpenses: {
        type: Array,
        default: []
    },

    statistics: {
        type: Array,
        default: []
    }
})

const markExpenseAsPaid = function (upcomingExpense) {
    router
        .put(route('expense.mark-paid', [upcomingExpense.id]));
};

</script>

<template>
    <AppLayout title="Dashboard">
        <div class="grid" style="grid-auto-rows: auto;">
            <div class="col-12 md:col-4 lg:col-4">
                <Panel header="Net Income" class="relative">
                    <template #icons>
                        <AddIncomeModal />
                    </template>

                    <div class="text-2xl font-bold">
                        {{ totalIncome }} zł
                    </div>
                </Panel>
            </div>

            <div class="col-12 md:col-4 lg:col-4">
                <Panel header="Total Expenses" class="relative">
                    <template #icons>
                        <AddExpenseModal />
                    </template>

                    <div class="text-2xl font-bold">
                        {{ totalExpense }} zł
                    </div>
                </Panel>
            </div>

            <div class="col-12 md:col-4 lg:col-4">
                <Panel header="Gross Income">
                    <div class="text-2xl font-bold">
                        {{ totalIncome - totalExpense }} zł
                    </div>
                </Panel>
            </div>

            <div class="col-12 md:col-4 lg:col-4">
                <Panel header="Upcoming Expanses" class="p-panel-content-no-padding">
                    <template #icons>

                    </template>

                    <div class="text-center" v-if="!upcomingExpenses || upcomingExpenses.length === 0">
                        All good...
                    </div>
                    <div
                        v-else
                        class="py-3 px-4"
                        v-for="(upcomingExpense, index) in upcomingExpenses"
                        :key="index"
                        :class="{
                            'border-gray-300 border-bottom-1': index < (upcomingExpenses.length-1) && !isDateStringInPast(upcomingExpense.due_date),
                            'border-red-200 border-bottom-1': index < (upcomingExpenses.length-1) && isDateStringInPast(upcomingExpense.due_date),
                            'bg-red-100': isDateStringInPast(upcomingExpense.due_date),
                        }"
                    >
                        <div class="flex">
                            <div class="flex-grow-1 text-sm">{{ upcomingExpense.expense.name }}</div>
                            <div class="flex-grow-1 text-right text-xs">{{ parseDateString(upcomingExpense.due_date) }}</div>
                        </div>
                        <div class="flex">
                            <div class="flex-grow-1 text-sm text-xl font-bold">{{ upcomingExpense.value.toFixed(2) }}zł</div>
                            <div class="flex-grow-0 text-right text-xs">
                                <Button
                                    @click="() => markExpenseAsPaid(upcomingExpense)"
                                    size="small"
                                >Mark Paid</Button>
                            </div>
                        </div>
                    </div>
                </Panel>
            </div>

            <div class="col-12 lg:col-8">
                <Panel header="Income/Expanse of 2023 by month">
                    <IncomeExpanseChart />
                </Panel>
            </div>
        </div>
    </AppLayout>
</template>
