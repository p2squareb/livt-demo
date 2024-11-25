<script lang="ts" setup>
import { useVModel } from '@vueuse/core';

type OptionsType = {
	name: string,
	value: string,
}

interface InputProps {
	modelValue?: string;
	options?: OptionsType[];
	placeholder?: string;
}

const props = withDefaults(defineProps<InputProps>(), {
	options: () => [],
});

const emit = defineEmits(['update:modelValue']);

const model = useVModel(props, 'modelValue', emit)
</script>
  
<template>
	<select 
		v-model="model" 
		class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-md block w-full pr-8 
		dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white focus:ring-0"
	>
		<option v-if="placeholder" value="" selected>{{ placeholder }}</option>
		<option
			v-for="(option, index) in options"
			:key="index"
			:value="option.value"
		>
			{{ option.name }}
		</option>
	</select>
</template>
