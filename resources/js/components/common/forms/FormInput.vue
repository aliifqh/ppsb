<template>
    <div>
        <label :for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
        <input
            :type="type"
            :id="name"
            :name="name"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :disabled="disabled"
            class="input-field"
            :class="{'border-red-500': error}"
        />
        <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
    </div>
</template>

<script>
export default {
    name: 'FormInput',
    props: {
        modelValue: [String, Number],
        name: String,
        label: String,
        type: {
            type: String,
            default: 'text'
        },
        error: String,
        disabled: Boolean
    },
    emits: ['update:modelValue']
}
</script>

<style scoped>
.input-field {
    @apply block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm;
}
.input-field:disabled {
    @apply bg-gray-100 cursor-not-allowed;
}
</style>