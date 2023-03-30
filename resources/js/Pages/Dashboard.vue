<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import Card from "@/Components/Card.vue";
import IncomeExpanseChart from "@/Components/IncomeExpanseChart.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AddIncomeModal from "@/Components/Modals/AddIncomeModal.vue";
import AddExpenseModal from "@/Components/Modals/AddExpenseModal.vue";
import { parseDateString, isDateStringInPast } from "../helpers";
import { Inertia } from '@inertiajs/inertia';

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
    Inertia
        .put(route('expense.mark-paid', [upcomingExpense.id]));
};

</script>

<template>
    <AppLayout title="Dashboard">
        <div class="md:flex md:flex-row pt-5 md:gap-x-5">
            <Card class="md:basis-1/3" title="Net Income">
                <template #action>
                    <AddIncomeModal />
                </template>

                <div class="text-2xl font-bold">
                    {{ totalIncome }} zł
                </div>
            </Card>

            <Card class="md:basis-1/3 mt-5 md:mt-0" title="Total Expenses">
                <template #action>
                    <AddExpenseModal />
                </template>

                <div class="text-2xl font-bold">
                    {{ totalExpense }} zł
                </div>
            </Card>

            <Card class="md:basis-1/3 mt-5 md:mt-0" title="Gross Income">
                <div class="text-2xl font-bold">
                    {{ totalIncome - totalExpense }} zł
                </div>
            </Card>
        </div>

        <div class="md:flex md:flex-row pt-5 md:gap-x-5">
            <Card class="md:basis-1/3 mt-0" title="Upcoming Expanses">
                <div class="text-center" v-if="!upcomingExpenses || upcomingExpenses.length === 0">
                    All good...
                </div>
                <div
                    v-else
                    class="px-4 py-5 sm:px-6 border-b-ros"
                    v-for="(upcomingExpense, index) in upcomingExpenses"
                    :key="index"
                    :class="{
                        'border-b-gray-200 border-b-2': index < (upcomingExpense.length-1) && !isDateStringInPast(upcomingExpense.due_date),
                        'border-b-rose-300 border-b-2': index < (upcomingExpense.length-1) && isDateStringInPast(upcomingExpense.due_date),
                        'bg-rose-100': isDateStringInPast(upcomingExpense.due_date),
                    }"
                >
                    <div class="flex">
                        <div class="grow text-sm">{{ upcomingExpense.expense.name }}</div>
                        <div class="grow-0 text-right text-xs">{{ parseDateString(upcomingExpense.due_date) }}</div>
                    </div>
                    <div class="flex">
                        <div class="grow text-sm text-xl font-bold">{{ upcomingExpense.value.toFixed(2) }}zł</div>
                        <div class="grow-0 text-right text-xs">
                            <PrimaryButton
                                @click="() => markExpenseAsPaid(upcomingExpense)"
                            >Mark Paid</PrimaryButton>
                        </div>
                    </div>
                </div>
            </Card>

            <Card class="md:basis-2/3 mt-5 md:mt-0" title="Income/Expanse of 2023 by month">
                <IncomeExpanseChart />
            </Card>
        </div>
    </AppLayout>
</template>
