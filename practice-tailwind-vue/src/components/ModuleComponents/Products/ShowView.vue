<script setup lang="ts">
import { reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useProductStore } from "@/stores/product.js";
import Swal from 'sweetalert2';
import DateFormatter from "@/lib/date.ts";

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';
import _ from 'lodash';

const route = useRoute();
const recordID = route.params.id;
const productStore = useProductStore();
const product = reactive({
    name: '',
    category: '',
    price: '',
    image: '',
    sku: '',
    total_stock: '',
    units_sold: '',
    revenue: '',
    unit: '',
    created_at: '',
});

onMounted(async () => {
    Swal.fire({
        title: 'Loading Record...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
        }
    });

    let productdata = await productStore.getProduct(recordID);
    console.log(productdata);
    if (productdata) {
        product.name = productdata.name;
        product.category = productdata.product_category.name;
        product.price = productdata.selling_price;
        product.image = productdata.image;
        product.sku = productdata.sku;
        product.total_stock = productdata.product_stats_data.total_stock;
        product.units_sold = productdata.product_stats_data.units_sold;
        product.revenue = productdata.product_stats_data.revenue;
        product.created_at = productdata.created_at;
        product.unit = productdata.unit;
    }

    Swal.close();
});
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <div class="flex flex-col h-fit w-full p-4 mb-2 bg-white rounded-2xl shadow gap-6">
                <div class="flex flex-row justify-between">
                    <div class="flex flex-row items-center gap-3">
                        <div class="w-2 h-10 rounded-full bg-gradient-to-b from-green-500 to-green-700"></div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-800">Product Details</h1>
                    </div>

                    <div class="flex items-center gap-1">
                        <router-link :to="'/products'" class="btn-danger"> Back </router-link>
                        <router-link :to="'/products/edit/' + recordID" class="btn-primary"> Edit </router-link>
                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-center lg:items-start">
                        <div class="flex flex-col items-center min-w-48 h-48">
                            <img :src="product.image || '/icons/default_product.svg'"alt="" class="min-w-48 h-48 rounded-xl object-cover shadow-md ring-2 ring-gray-300 hover:scale-105 transition-transform duration-300" />
                        </div>

                        <div class="flex flex-col gap-4 w-full">
                            <h1 class="text-2xl font-bold text-gray-800"> {{ product.name }} </h1>

                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5 lg:gap-10 xl:gap-4 mb-3">
                                <div class="flex flex-col gap-1">
                                    <h2 class="text-sm font-medium text-gray-500"> SKU </h2>

                                    <span class="text-lg font-semibold text-gray-800">
                                        {{  product.sku || 'N/A' }}
                                    </span>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <h2 class="text-sm font-medium text-gray-500"> Category </h2>
                                    
                                    <span class="text-lg font-semibold text-gray-800 capitalize">
                                        {{ product.category }} 
                                    </span>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <h2 class="text-sm font-medium text-gray-500"> Price </h2>

                                    <span class="text-lg font-semibold text-green-600">
                                        ₱ {{product.price}} / {{ product.unit }}
                                    </span>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <h2 class="text-sm font-medium text-gray-500"> Created At </h2>

                                    <span class="text-lg font-semibold text-gray-800">
                                        {{DateFormatter.toReadable(product.created_at)}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                        <div class="flex flex-col w-full items-start bg-gradient-to-br from-green-200 to-white shadow-sm rounded-lg p-4 hover:shadow-md transition ring-2 ring-gray-200">
                            <h3 class="text-sm font-medium text-gray-600">Available Stock</h3>

                            <h2 class="text-2xl font-bold text-green-700">{{ product.total_stock }} {{ product.unit }}s</h2>
                        </div>

                        <div class="flex flex-col w-full items-start bg-gradient-to-br from-green-200 to-white shadow-sm rounded-lg p-4 hover:shadow-md transition ring-2 ring-gray-200">
                            <h3 class="text-sm font-medium text-gray-600">Units Sold</h3>

                            <h2 class="text-2xl font-bold text-green-700"> {{ Number(product.units_sold) % 1 === 0 ? Number(product.units_sold) : Number(product.units_sold).toFixed(2) }} {{ product.unit }}s </h2>
                        </div>

                        <div class="flex flex-col w-full items-start bg-gradient-to-br from-green-200 to-white shadow-sm rounded-lg p-4 hover:shadow-md transition ring-2 ring-gray-200">
                            <h3 class="text-sm font-medium text-gray-600">Total Revenue</h3>

                            <h2 class="text-2xl font-bold text-green-700">
                                ₱ {{ product.revenue }}
                            </h2>
                        </div>

                        <div class="flex flex-col w-full items-start bg-gradient-to-br from-green-200 to-white shadow-sm rounded-lg p-4 hover:shadow-md transition ring-2 ring-gray-200">
                            <h3 class="text-sm font-medium text-gray-600">Stock Status</h3>

                            <h2 :class="['text-2xl font-bold', product.total_stock <= 5 ? 'text-red-600' : 'text-green-700']">
                                {{ product.total_stock <= 5 ? 'Low Stock' : 'In Stock' }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>