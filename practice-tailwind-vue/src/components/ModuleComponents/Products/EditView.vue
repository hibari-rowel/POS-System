<script setup lang="ts">
import { computed, onMounted, reactive } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useProductStore } from "@/stores/product.js";
import Swal from 'sweetalert2';
import { fireToast } from "@/lib/toast";

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';
import TextField from '@/components/FieldComponents/TextField.vue';
import ImageUploadField from '@/components/FieldComponents/ImageUploadField.vue';
import TextAreaField from '@/components/FieldComponents/TextAreaField.vue';
import DecimalField from '@/components/FieldComponents/DecimalField.vue';
import DropdownApiSearchField from '@/components/FieldComponents/DropdownApiSearchField.vue';

import { productValidation } from '@/lib/validations/ProductValidation';

const productStore = useProductStore();
const router = useRouter();
const route = useRoute();
const recordID = route.params.id;
const header = { 
    title: 'Products',
    bread_crumbs: [
        {name: "Products", path: '/products'},
        {name: "Edit",},
    ],
};

const rules = computed(() => productValidation(form));
const form = reactive({
    name: '',
    description: '',
    sku: '',
    unit: '',
    product_category: '',
    selling_price: '',
    image: null,
});

const submitForm = async () => {
    const confirmation = await Swal.fire({
        icon: "question",
        title: "Confirmation",
        text: "Are you sure you want to edit this product?",
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

        let isSuccessfull = await productStore.updateProduct(form, rules, recordID);

        Swal.close();

        if (isSuccessfull) {
            fireToast("success", 'User updated successfully.');
            router.push('/products');
        }
    }
};

onMounted(async () => {
    Swal.fire({
        title: 'Loading Record...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
        }
    });

    const product = await productStore.getProduct(recordID);

    if (product) {
        form.name = product.name
        form.description = product.description
        form.sku = product.sku
        form.unit = product.unit
        form.product_category = product.product_category
        form.selling_price = product.selling_price
        form.image = product.image
    }

    Swal.close();
});
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center gap-1">
                        <router-link :to="'/products/show/' + recordID" class="btn-danger"> Cancel </router-link>
                        <button class="btn-primary" @click="submitForm()"> Save </button>
                    </div>
                </template>
            </Header>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 h-full mb-4 rounded-2xl">
                <div class="bg-white md:col-span-2 xl:col-span-1 rounded-xl shadow-md p-5 flex flex-col items-center justify-start">
                    <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2 w-full text-center">
                        Product Image
                    </h3>

                    <ImageUploadField :size="'h-72'" :is_required="false" :errors="productStore.errors.image" v-model="form.image"/>
                </div>

                <div class="flex flex-col md:col-span-3 xl:col-span-4 gap-6 bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-700 border-b pb-2"> Product Information </h3>

                    <div class="grid grid-cols-1 gap-4">
                        <TextField id="name" label="Product Name" placeholder="Enter product name" :is_required="true" 
                                   v-model="form.name" :errors="productStore.errors.name" 
                                   @clearErrors="productStore.cleanErrors('name')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <TextField id="sku" label="SKU" placeholder="Enter SKU" :is_required="false" 
                                   v-model="form.sku" :errors="productStore.errors.sku" 
                                   @clearErrors="productStore.cleanErrors('sku')" />

                        <DropdownApiSearchField :label="'Category'" :placeholder="'Enter category'" 
                            :is_required="true" :is_disabled="false" v-model="form.product_category" 
                            :api_url="'/api/product_categories/get_dropdown_list'" 
                            :default_options="[form.product_category]"
                            :errors="productStore.errors.product_category" 
                            @clearErrors="productStore.cleanErrors('status')"/>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <DecimalField id="selling_price" label="Selling Price" placeholder="Enter selling price" :is_required="true" 
                                v-model="form.selling_price" :errors="productStore.errors.selling_price" 
                                @clearErrors="productStore.cleanErrors('selling_price')" 
                                :mask_params="{prefix: '₱ ', groupSeparator: ',', digits: 2, digitsOptional: false,}"/>

                        <TextField id="unit" label="Unit" placeholder="Enter unit" :is_required="true" 
                                v-model="form.unit" :errors="productStore.errors.unit" 
                                @clearErrors="productStore.cleanErrors('unit')" />
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <TextAreaField id="description" label="Description" placeholder="Enter category description (optional)"
                                       :is_required="false" v-model="form.description" :errors="productStore.errors.description"
                                       @clearErrors="productStore.cleanErrors('description')" />
                    </div>
                </div>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>