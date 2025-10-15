<script setup lang="ts">
import _ from 'lodash';
import { computed } from 'vue';

const model = defineModel();

const props = defineProps({
    'id': String,
    'label': String,
    'placeholder': String,
    'is_required': Boolean,
    'is_disabled': Boolean,
    'errors': Array,
    'mode': {type: String, required: true, validator: (value) => ['date', 'datetime'].includes(value),}
});

const rules = computed(() => {
    switch (props.mode) {
        case 'date':
            return { hours: 0, minutes: 0, seconds: 0, milliseconds: 0,};
        default:
            return {};
    }
});

const mask = computed(() => {
    switch (props.mode) {
        case 'date':
            return { modelValue: 'YYYY-MM-DD', };
        case 'datetime':
            return { modelValue: 'YYYY-MM-DD HH:mm', };
        default:
            return{};
    }
});

const emit = defineEmits(['clearErrors']);
const onInput = () => {
  emit('clearErrors');
};
</script>

<template>
    <div class="w-full">
        <label :for="id" class="form-label"> {{ label }} <span class="text-red-500" v-if="is_required">*</span></label>

        <div class="text-box" :class="!_.isEmpty(errors) ? 'text-box-danger' : 'text-box-default'">
            <VDatePicker v-model.string="model" :update-on-input="false" :mode="mode" :rules="rules" :masks="mask" hide-time-header @update:modelValue="onInput">
                <template v-slot="{ inputValue, inputEvents }">
                    <input class="border-0 outline-none bg-transparent w-full placeholder-gray-400"
                        :value="inputValue" v-on="inputEvents" disabled :placeholder="placeholder"/>
                </template>
            </VDatePicker>
        </div>

        <div class="text-red-500 text-sm mt-1" v-if="!_.isEmpty(errors)" v-html="_.join(errors, '<br>')"></div>
    </div>
</template>

<style scoped>
</style>