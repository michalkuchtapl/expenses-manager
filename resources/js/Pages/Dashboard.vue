<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import Card from "@/Components/Card.vue";
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <Card>
                    January 2023
                </Card>

                <div class="md:flex md:flex-row pt-5 md:gap-x-5">
                    <Card class="md:basis-1/3" title="Net Income">
                        <template #action>
                            <button class="bg-green-500 text-black px-3 py-1 mb-1 rounded-lg" title="Add Income">
                                +
                            </button>
                        </template>

                        <div class="text-2xl font-bold">
                            0 zł
                        </div>
                    </Card>

                    <Card class="md:basis-1/3 mt-5 md:mt-0" title="Total Expenses">
                        <template #action>
                            <button class="bg-green-500 text-black px-3 py-1 mb-1 rounded-lg" title="Add Expense">
                                +
                            </button>
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
            </div>
        </div>
    </AppLayout>
</template>
