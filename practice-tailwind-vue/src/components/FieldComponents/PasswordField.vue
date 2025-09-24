<script setup lang="ts">
import _ from 'lodash';

const model = defineModel();

const props = defineProps({
    'id': String,
    'label': String,
    'placeholder': String,
    'is_required': Boolean,
    'is_disabled': Boolean,
    'errors': Array,
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
            <input type="password" :id="id" class="border-0 outline-none bg-transparent w-full placeholder-gray-400" 
                :placeholder="placeholder" v-model="model" :disabled="is_disabled" @input="onInput">
        </div>

        <div class="text-red-500 text-sm mt-1" v-if="!_.isEmpty(errors)" v-html="_.join(errors, '<br>')"></div>
    </div>
</template>

<style scoped>
</style>