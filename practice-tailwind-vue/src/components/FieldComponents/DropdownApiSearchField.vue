<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from '@/lib/axios';
import _ from 'lodash';

const model = defineModel();

const props = defineProps({
    'label': String,
    'placeholder': String,
    'is_required': Boolean,
    'is_disabled': Boolean,
    'api_url': { type: String, required: true },
    'errors': Array,
});

const emit = defineEmits(['clearErrors']);
const onChange = () => {
    emit('clearErrors');
};

const options = ref([])
let searchTimeout = null

const fetchData = async (search = '', loading) => {
    if (_.isEmpty(search)) {
        return;
    }

    loading(true);

    try {
        let response = await axios.get(props.api_url, {params: { 'search': search }});
        options.value = response.data;
    } catch (error) {
        console.error('API Select error:', error);
    } finally {
        loading(false);
    }
}

const ensureSelectedInOptions = () => {
    if (model.value && !options.value.find(option => option.id === model.value.id)) {
        options.value.push(model.value);
    }
};

const handleSearch = (search, loading) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => fetchData(search, loading), 600);
}
</script>

<template>
    <div class="w-full">
        <label class="form-label"> {{ label }} <span class="text-red-500" v-if="is_required">*</span> </label>

        <v-select :placeholder="placeholder" :class="!_.isEmpty(errors) ? 'dropdown-danger' : 'dropdown-default'" :filterable="false" 
            :options="options" @search="handleSearch" :label="'name'" v-model="model" :clearable="false" @update:modelValue="onChange"/>

        <div class="text-red-500 text-sm mt-1" v-if="!_.isEmpty(errors)" v-html="_.join(errors, '<br>')"></div>
    </div>
</template>

<style scoped>
</style>