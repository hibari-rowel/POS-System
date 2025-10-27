<script setup lang="ts">
import { computed, onMounted, reactive } from "vue";
import { onBeforeRouteLeave, useRouter, useRoute } from "vue-router";
import Swal from 'sweetalert2';
import { fireToast } from "@/lib/toast";
import { useProductCategoryStore } from "@/stores/product_category.js";
import { categoryValidation } from '@/lib/validations/CategoryValidation.ts';

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';
import TextField from '@/components/FieldComponents/TextField.vue';
import ImageUploadField from '@/components/FieldComponents/ImageUploadField.vue';
import TextAreaField from '@/components/FieldComponents/TextAreaField.vue';

const router = useRouter();
const productCategoryStore = useProductCategoryStore();
const route = useRoute();
const recordID = route.params.id;
const header = { 
    title: 'Product Categories',
    bread_crumbs: [
        {name: "Product Categories", path: '/product-categories'},
        {name: "Edit",},
    ],
};

const rules = computed(() => categoryValidation(form));
const form = reactive({
    name: '',
    description: '',
    image: null,
});

const submitForm = async () => {
    const confirmation = await Swal.fire({
        icon: "question",
        title: "Confirmation",
        text: "Are you sure you want to edit this category?",
        showCancelButton: true,
        confirmButtonText: "Save",
        buttonsStyling: false,
        customClass: {
            confirmButton: "btn-primary mx-10",
            cancelButton: "btn-danger mx-10",
        }
    });

    if (confirmation.isConfirmed) {
        Swal.fire({
            title: 'Saving...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()  
            }
        });

        let isSuccessfull = await productCategoryStore.updateCategory(form, rules, recordID);

        Swal.close();

        if (isSuccessfull) {
            fireToast("success", 'Category updated successfully.');
            router.push('/product-categories');
        }
    }
};

onMounted(async () => {
    const user = await productCategoryStore.getCategory(recordID);
    if (user) {
        form.name = user.name;
        form.description = user.description;
        form.image = user.image;
    }
});

onBeforeRouteLeave(() => {
    productCategoryStore.resetErrors();
});
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center gap-1">
                        <router-link :to="'/product-categories'" class="btn-danger"> Cancel </router-link>
                        <button class="btn-primary" @click="submitForm()"> Save </button>
                    </div>
                </template>
            </Header>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 h-full mb-4 rounded-2xl">
                <div class="bg-white md:col-span-2 xl:col-span-1 rounded-xl shadow-md p-5 flex flex-col items-center justify-start">
                    <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2 w-full text-center">
                        Category Image
                    </h3>

                    <ImageUploadField :size="'h-72'" :is_required="false" :errors="productCategoryStore.errors.image" v-model="form.image"/>
                </div>

                <div class="flex flex-col md:col-span-3 xl:col-span-4 gap-6 bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-700 border-b pb-2"> Category Information </h3>

                    <div class="grid grid-cols-1 gap-4">
                        <TextField id="name" label="Category Name" placeholder="Enter category name" :is_required="true" 
                                   v-model="form.name" :errors="productCategoryStore.errors.name" 
                                   @clearErrors="productCategoryStore.cleanErrors('name')" />
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <TextAreaField id="description" label="Description" placeholder="Enter category description (optional)"
                                       :is_required="false" v-model="form.description" :errors="productCategoryStore.errors.description"
                                       @clearErrors="productCategoryStore.cleanErrors('description')" />
                    </div>
                </div>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>
