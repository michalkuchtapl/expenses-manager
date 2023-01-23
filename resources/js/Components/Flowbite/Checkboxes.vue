<script setup>
import {ref} from "vue";

const props = defineProps({
    label: String,
    options: {
        type: Array,
        default: []
    },
    modelValue: {
        type: Array,
        default: []
    }
})
const emit = defineEmits(['update:modelValue']);

const changeState = (value) => {
    const tempValues = JSON.parse(JSON.stringify(props.modelValue));

    if (tempValues.includes(value)) {
        tempValues.splice(tempValues.indexOf(value), 1);
    } else {
        tempValues.push(value);
    }
    console.log(tempValues);
    emit('update:modelValue', tempValues);
}

const showDropdown = ref(false);

function toggleDropdown() {
    showDropdown.value = !showDropdown.value;
}
</script>

<template>
    <div class="relative">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ label }}</label>
        <button @click="toggleDropdown" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Select {{(modelValue.length > 0 ? `(${modelValue.length})` : '') }} <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>

        <!-- Dropdown menu -->
        <div v-if="showDropdown" class="z-10 bg-white rounded shadow w-60 dark:bg-gray-700 absolute top-full left-0 border-2 border-gray-100">
            <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200">
                <li v-for="(option, key) in options" :key="key">
                    <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                        <input :checked="modelValue.includes(option.value)" @input="() => {changeState(option.value)}" id="checkbox-item-11" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                        <label for="checkbox-item-11" class="w-full ml-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">{{option.label}}</label>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
