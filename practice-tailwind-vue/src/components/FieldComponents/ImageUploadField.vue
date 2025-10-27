<script setup lang="ts">
import { ref, watch } from 'vue';
import _ from 'lodash';

const props = defineProps({
    'label': String,
    'default_image': { type: String, default: '/icons/default_image.svg', },
    'is_required': Boolean,
    'size': { type: String, default: 'h-70', },
    'errors': Array,
});

const previewImage = ref <string | null> (props.default_image);
const model = defineModel();

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files ? target.files[0] : null;
    model.value = file;
};

watch(model, (newVal) => {
    if (typeof newVal === 'string' && newVal !== '') {
        previewImage.value = newVal;
    } else if (newVal instanceof File) {
        previewImage.value = URL.createObjectURL(newVal);
    } else {
        previewImage.value = props.default_image;
    }
}, { immediate: true });
</script>

<template>
    <div class="w-full">
        <label class="form-label"> {{ label }} <span class="text-red-500" v-if="is_required">*</span></label>

        <div class="upload-image-container relative bg-center bg-no-repeat bg-contain group" 
             :class="[size]"
             :style="previewImage ? { backgroundImage: `url(${previewImage})` } : {}">
            <div class="absolute inset-0 bg-gray-300/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none"></div>

            <input type="file" id="fileInput" class="hidden" accept="image/*" @change="handleFileChange"/>

            <label class="upload-image-button z-10 group-hover:opacity-100 group-hover:bg-gray-100 group-hover:shadow-md" for="fileInput">
                <img class="" src="/icons/upload.svg" alt="Upload" />
            </label>
        </div>

        <div class="text-red-500 text-sm mt-1" v-if="!_.isEmpty(errors)" v-html="_.join(errors, '<br>')"></div>
    </div>
</template>

<style scoped>
</style>