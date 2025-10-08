<script setup lang="ts">
import _ from 'lodash';

const model = defineModel <string> ();

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
            <textarea type="text" :id="id" class="border-0 outline-none bg-transparent w-full placeholder-gray-400" 
                :placeholder="placeholder" :disabled="is_disabled" rows="8" v-model="model" @input="onInput">
            </textarea>
        </div>

        <div class="text-red-500 text-sm mt-1" v-if="!_.isEmpty(errors)" v-html="_.join(errors, '<br>')"></div>
    </div>
</template>

<style scoped>
</style>