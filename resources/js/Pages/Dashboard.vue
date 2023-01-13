<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import Card from "@/Components/Card.vue";
import IncomeExpanseChart from "@/Components/IncomeExpanseChart.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AddIncomeModal from "@/Components/Modals/AddIncomeModal.vue";
import AddExpenseModal from "@/Components/Modals/AddExpenseModal.vue";

const props = defineProps({
    upcomingExpanses: {
        type: Array,
        default: [
            {
                danger: true,
                title: "Flat",
                amount: 1100.0,
                date: "2023-01-3"
            },
            {
                danger: true,
                title: "Credit 1",
                amount: 250.0,
                date: "2023-01-14"
            },
            {
                danger: false,
                title: "Credit 2",
                amount: 1900.0,
                date: "2023-01-14"
            },
            {
                danger: false,
                title: "Something dangerous",
                amount: 1900.0,
                date: "2023-01-14"
            },
        ]
    }
})
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="md:flex md:flex-row pt-5 md:gap-x-5">
            <Card class="md:basis-1/3" title="Net Income">
                <template #action>
                    <AddIncomeModal />
                </template>

                <div class="text-2xl font-bold">
                    0 zł
                </div>
            </Card>

            <Card class="md:basis-1/3 mt-5 md:mt-0" title="Total Expenses">
                <template #action>
                    <AddExpenseModal />
                </template>

                <div class="text-2xl font-bold">
                    0 zł
                </div>
            </Card>

            <Card class="md:basis-1/3 mt-5 md:mt-0" title="Gross Income">
                <div class="text-2xl font-bold">
                    0 zł
                </div>
            </Card>
        </div>

        <div class="md:flex md:flex-row pt-5 md:gap-x-5">
            <Card class="md:basis-1/3 mt-0" title="Upcoming Expanses">
                <div class="text-center" v-if="!upcomingExpanses || upcomingExpanses.length === 0">
                    All good...
                </div>
                <div
                    v-else
                    class="px-4 py-5 sm:px-6 border-b-ros"
                    v-for="(upcomingExpanse, index) in upcomingExpanses"
                    :key="index"
                    :class="{
                        'border-b-gray-200 border-b-2': index < (upcomingExpanses.length-1) && !upcomingExpanse.danger,
                        'border-b-rose-300 border-b-2': index < (upcomingExpanses.length-1) && upcomingExpanse.danger,
                        'bg-rose-100': upcomingExpanse.danger,
                    }"
                >
                    <div class="flex">
                        <div class="grow text-sm">{{ upcomingExpanse.title }}</div>
                        <div class="grow-0 text-right text-xs">{{ upcomingExpanse.date }}</div>
                    </div>
                    <div class="flex">
                        <div class="grow text-sm text-xl font-bold">{{ upcomingExpanse.amount.toFixed(2) }}zł</div>
                        <div class="grow-0 text-right text-xs">
                            <PrimaryButton
                                @onClick="() => {
                                    upcomingExpanses = upcomingExpanses.splice(index, 1);
                                }"
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
