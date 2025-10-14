<script setup lang="ts">
import { computed, reactive } from "vue";
import { onBeforeRouteLeave, useRouter } from "vue-router";
import { useStockStore } from "@/stores/stock.js";
import Swal from 'sweetalert2';
import { fireToast } from "@/lib/toast";

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';
import TextField from '@/components/FieldComponents/TextField.vue';
import TextAreaField from '@/components/FieldComponents/TextAreaField.vue';
import DecimalField from '@/components/FieldComponents/DecimalField.vue';
import DropdownApiSearchField from '@/components/FieldComponents/DropdownApiSearchField.vue';
import VueTailwindDatepicker from "vue-tailwind-datepicker";

import { stockValidation } from '@/lib/validations/StockValidation.ts';

const router = useRouter();
const stockStore = useStockStore();
const header = { 
    title: 'Stocks',
    bread_crumbs: [
        {name: "Stocks", path: '/stocks'},
        {name: "Create",},
    ],
};

const rules = computed(() => stockValidation(form));

const form = reactive({
    supplier_name: '',
    product: '',
    price: '0',
    quantity: '0',
    unit: '',
    stock_date: '',
    description: '',
    subtotal: '',
});

const total = computed(() => {
    let cleanQuantity = parseFloat((form.quantity || '').replace(/[₱,]/g, ''));
    let cleanPrice = parseFloat((form.price || '').replace(/[₱,]/g, ''));
    let subtotal = (cleanQuantity * cleanPrice).toFixed(2);

    return subtotal;
});

const submitForm = async () => {
    form.subtotal = total.value;
    console.log(form)
    const confirmation = await Swal.fire({
        icon: "question",
        title: "Confirmation",
        text: "Are you sure you want to save this stock?",
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

        let isSuccessfull = await stockStore.createStock(form, rules);

        Swal.close();

        if (isSuccessfull) {
            fireToast("success", 'Stock created successfully.');
            router.push('/stocks');
        }
    }
};

onBeforeRouteLeave(() => {
    stockStore.resetErrors();
});
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center gap-1">
                        <router-link :to="'/stocks'" class="btn-danger"> Cancel </router-link>
                        
                        <button class="btn-primary" @click="submitForm()"> Save </button>
                    </div>
                </template>
            </Header>

            <div class="flex flex-col h-full rounded-2xl">
                <div class="flex flex-col gap-6 bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-700 border-b pb-2"> Stock Information </h3>

                    <div class="grid grid-cols-1 gap-4">
                        <DropdownApiSearchField :label="'Product'" :placeholder="'Enter product'" 
                            :is_required="true" :is_disabled="false" v-model="form.product" 
                            :api_url="'/api/products/get_dropdown_list'" 
                            :errors="stockStore.errors.product"
                            @clearErrors="stockStore.cleanErrors('product')"/>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <TextField id="supplier_name" label="Supplier Name" placeholder="Enter supplier name" 
                                :is_required="true" v-model="form.supplier_name" 
                                :errors="stockStore.errors.supplier_name" 
                                @clearErrors="stockStore.cleanErrors('supplier_name')" />

                        <TextField id="stock_date" label="Stock Date" placeholder="Enter stock date" 
                                :is_required="true" v-model="form.stock_date" 
                                :errors="stockStore.errors.stock_date" 
                                @clearErrors="stockStore.cleanErrors('stock_date')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <DecimalField id="quantity" label="Quantity" placeholder="Enter quantity" :is_required="true" 
                                v-model="form.quantity" :errors="stockStore.errors.quantity" 
                                @clearErrors="stockStore.cleanErrors('quantity')" 
                                :mask_params="{groupSeparator: ',', digits: 2, digitsOptional: false,}"/>

                        <TextField id="unit" label="Unit" placeholder="Enter unit" :is_required="true" 
                                v-model="form.unit" :errors="stockStore.errors.unit" 
                                @clearErrors="stockStore.cleanErrors('unit')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">        
                        <DecimalField id="price" label="Price" placeholder="Enter price" :is_required="true" 
                                v-model="form.price" :errors="stockStore.errors.price" 
                                :mask_params="{prefix: '₱ ', groupSeparator: ',', digits: 2, digitsOptional: false,}"
                                @clearErrors="stockStore.cleanErrors('price')" />

                        <DecimalField id="subtotal" label="Total Price" placeholder="Total Price" :is_required="true" 
                                v-model="total" :errors="stockStore.errors.subtotal" :is_disabled="true"
                                :mask_params="{prefix: '₱ ', groupSeparator: ',', digits: 2, digitsOptional: false,}"
                                @clearErrors="stockStore.cleanErrors('subtotal')" />
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <TextAreaField id="description" label="Description" placeholder="Enter category description (optional)"
                                       :is_required="false" v-model="form.description" :errors="stockStore.errors.description"
                                       @clearErrors="stockStore.cleanErrors('description')" />
                    </div>
                </div>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>