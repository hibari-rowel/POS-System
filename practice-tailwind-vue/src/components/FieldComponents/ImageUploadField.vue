<script setup lang="ts">
import { ref } from 'vue';
import _ from 'lodash';

const defaultImage = '/icons/default_profile.svg';
const previewImage = ref <string | null> (defaultImage);
const model = defineModel();
const props = defineProps({
    'label': String,
    'is_required': Boolean,
    'size': { type: String, default: 'h-70', },
    'errors': Array,
});

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files ? target.files[0] : null;
    model.value = file;

    previewImage.value = (file) ? URL.createObjectURL(file) : defaultImage;
};
</script>

<template>
    <div class="w-full">
        <label class="form-label"> {{ label }} <span class="text-red-500" v-if="is_required">*</span></label>

        <div class="upload-image-container relative bg-center bg-no-repeat group" 
             :class="[previewImage === defaultImage ? 'bg-contain' : 'bg-cover', size]"
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