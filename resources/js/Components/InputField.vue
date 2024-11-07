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

        <div class="relative mt-1 rounded-md">
            <div
                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
            >
                <span class="grid place-content-center text-sm text-slate-400">
                    <i :class="`bi bi-${icon}`"></i>
                </span>
            </div>
            <input
                :type="type"
                :name="label"
                :placeholder="placeholder"
                v-model="model"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
            />
        </div>
    </div>
</template>