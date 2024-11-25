<script lang="ts" setup>
import { computed, toRefs } from 'vue'

type CheckboxToggleSize = 'xs' | 'sm' | 'md' | 'lg'

const labelClasses = 'w-fit relative inline-flex items-center cursor-pointer'

const toggleClasses = 'relative bg-gray-200 peer-focus:outline-none peer-focus:ring-0 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[""] after:absolute after:bg-white after:border-gray-300 after:border after:rounded-full after:transition-all dark:border-gray-600 peer-checked:bg-blue-600'

const toggleBallClasses = 'text-md font-medium text-gray-900 dark:text-gray-300'

const labelOrder: Record<string, string> = {
    direct: '',
    reverse: 'flex-row-reverse',
}

const toggleBallOrder: Record<string, string> = {
    direct: 'ms-3',
    reverse: 'me-3',
}

const toggleSize: Record<CheckboxToggleSize, string> = {
    lg: 'w-14 h-7 after:top-0.5 after:start-[4px] after:h-6 after:w-6',
    md: 'w-11 h-6 after:top-[2px] after:start-[2px] after:h-5 after:w-5',
    sm: 'w-9 h-5 after:top-[2px] after:start-[2px] after:h-4 after:w-4',
    xs: 'w-7 h-4 after:top-[2px] after:start-[2px] after:h-3 after:w-3',
}

const toggleColor: Record<string, string> = {
    red: 'peer-focus:ring-0 peer-checked:bg-red-600',
    green: 'peer-focus:ring-0 peer-checked:bg-green-600',
    purple: 'peer-focus:ring-0 peer-checked:bg-purple-600',
    yellow: 'peer-focus:ring-0 peer-checked:bg-yellow-400',
    teal: 'peer-focus:ring-0 peer-checked:bg-teal-600',
    orange: 'peer-focus:ring-0 peer-checked:bg-orange-500',
}

interface CheckboxToggleProps {
    color?: string;
    disabled?: boolean;
    label?: string;
    modelValue?: boolean;
    size?: CheckboxToggleSize;
    reverse?: boolean;
}

const props = withDefaults(defineProps<CheckboxToggleProps>(), {
    color: '',
    disabled: false,
    label: '',
    modelValue: false,
    size: 'md',
    reverse: false,
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
</script>

<template>
    <label :class="[labelClasses, labelOrder[props.reverse ? 'reverse' : 'direct']]">
        <input
            v-model="model"
            :disabled="disabled"
            class="sr-only peer"
            type="checkbox"
        >
        <span :class="[toggleClasses, toggleSize[props.size], toggleColor[props.color]]" />
        <span :class="[toggleBallClasses, toggleBallOrder[props.reverse ? 'reverse' : 'direct']]">{{ label }}</span>
    </label>
</template>
  