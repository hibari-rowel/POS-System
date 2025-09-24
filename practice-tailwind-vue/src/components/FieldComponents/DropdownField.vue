<script setup>
import _ from 'lodash';

const model = defineModel();

const props = defineProps({
    'id': String,
    'label': String,
    'options': Array,
    'placeholder': String,
    'is_required': Boolean,
    'is_disabled': Boolean,
    'errors': Array,
});

</script>

<template>
    <div class="w-full">
        <label :for="id" class="form-label"> {{ label }} <span class="text-red-500" v-if="is_required">*</span></label>

        <v-select :id="id" :placeholder="placeholder" :class="!_.isEmpty(errors) ? 'dropdown-danger' : 'dropdown-default'" 
                  v-model="model" :options="options" :disabled="is_disabled" :label="'name'" :reduce="option => option.id"/>

        <div class="text-red-500 text-sm mt-1" v-if="errors">
            {{ _.join(errors, '<br>') }}
        </div>
    </div>
</template>

<style scoped>
</style>