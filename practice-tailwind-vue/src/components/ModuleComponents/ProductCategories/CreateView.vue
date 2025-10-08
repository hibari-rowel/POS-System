<script setup lang="ts">
import { reactive } from "vue";
import { useProductCategoryStore } from "@/stores/product_category.js";

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';
import TextField from '@/components/FieldComponents/TextField.vue';
import ImageUploadField from '@/components/FieldComponents/ImageUploadField.vue';
import TextAreaField from '@/components/FieldComponents/TextAreaField.vue';

const productCategoryStore = useProductCategoryStore();
const header = { 
    title: 'Product Categories',
    bread_crumbs: [
        {name: "Product Categories", path: '/product-categories'},
        {name: "Create",},
    ],
};

const form = reactive({
    name: '',
    description: '',
    image: null,
});
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center gap-1">
                        <router-link :to="'/product-categories'" class="btn-danger"> Cancel </router-link>
                        
                        <button class="btn-primary"> Save </button>
                    </div>
                </template>
            </Header>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 h-full mb-4 rounded-2xl">
                <div class="bg-white md:col-span-2 xl:col-span-1 rounded-xl shadow-md p-5 flex flex-col items-center justify-start">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 w-full text-center">
                        Category Image
                    </h3>

                    <ImageUploadField :size="'h-72'" :is_required="false" :errors="productCategoryStore.errors.image" v-model="form.image"/>

                    <p class="text-sm text-gray-500 mt-3 text-center">
                        Upload a representative image for this category.
                    </p>
                </div>

                <div class="flex flex-col md:col-span-3 xl:col-span-4 gap-6 bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2"> Category Information </h3>

                    <div class="grid grid-cols-1 gap-4">
                        <TextField id="name" label="Category Name" placeholder="Enter category name" :is_required="true" 
                                   v-model="form.name" :errors="productCategoryStore.errors.name" 
                                   @clearErrors="productCategoryStore.cleanErrors('name')" />
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <TextAreaField id="description" label="Description" placeholder="Enter category description (optional)"
                                       :is_required="false" v-model="form.description" :errors="productCategoryStore.errors.description"
                                       @clearErrors="productCategoryStore.cleanErrors('description')" 
                        />
                    </div>
                </div>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>
