<script setup>
const model = defineModel();
defineProps({
    label: String,
    icon: String,
    placeholder: {
        type: String,
        default: "",
    },
    type: {
        type: String,
        default: "text",
    },
});
</script>

<template>
    <div>
        <label
            :for="label"
            class="block text-sm font-medium text-slate-700 dark:text-slate-300"
        >
            {{ label }}
        </label>

        <div class="mt-1 rounded-md flex items-center">
            <input
                :type="type"
                :name="label"
                :placeholder="`${icon ? '' : ''}${placeholder}`"
                v-model="model"
                class="w-full ml-8 pl-5 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
            />
        </div>
    </div>
</template>