<script setup lang="ts">
import { computed } from 'vue'

interface RadioProps {
    modelValue?: string;
    id?: string;
    name?: string;
    value?: string;
    label?: string;
    disabled?: boolean;
    addClass?: string;
}
const radioDefaultClasses = 'w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-700 dark:border-gray-600 focus:ring-0 focus:ring-offset-0'
const radioLabelClasses = 'ms-2 text-md font-medium text-gray-900 dark:text-gray-300 ml-1 '

const props = withDefaults(defineProps<RadioProps>(), {
    modelValue: '',
    id: '',
    name: '',
    value: '',
    label: '',
    disabled: false,
    addClass: '',
})

const emit = defineEmits(['update:modelValue'])
const model = computed({
    get () {
        return props.modelValue
    },
    set (val) {
        emit('update:modelValue', val)
    },
})


const radioClasses = computed(() => {
    return props.addClass + ' ' + radioDefaultClasses
})

const labelClasses = computed(() => {
    return radioLabelClasses
})
</script>

<template>
    <input
        v-model="model"
        type="radio"
        :id="id"
        :disabled="disabled"
        :name="name"
        :value="value"
        :class="radioClasses"
    >
    <label
        :for="id"
        :class="labelClasses"
    >
        {{ label }}
    </label>
</template>


