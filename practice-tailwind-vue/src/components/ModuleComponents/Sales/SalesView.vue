<script setup lang="ts">
import { onMounted, ref, reactive, watch } from 'vue';
import _ from 'lodash';
import { useProductCategoryStore } from "@/stores/product_category.js";
import { useProductStore } from "@/stores/product.js";
import { useSaleStore } from "@/stores/sale.js";

import Base from '@/components/BaseComponents/Base.vue';

const productStore = useProductStore();
const productCategoryStore = useProductCategoryStore();
const saleStore = useSaleStore();
const isLoading = ref(false);
const isDrawerOpen = ref(window.innerWidth >= 768);
const searchQuery = ref('');
let searchTimeout : ReturnType <typeof setTimeout>;
const activeCategoryId = ref <string | null> (null);
const cashAmountInputMask = { alias: 'numeric', placeholder: '0', rightAlign: false, digits: 2, digitsOptional: false, };
const quantityInputMask = {alias: 'numeric', digits: 2, digitsOptional: false, placeholder: '1', min: 1, rightAlign: false, allowMinus: false};

const toggleOrderDrawer = () => {
    isDrawerOpen.value = !isDrawerOpen.value;
    localStorage.setItem('is_open_order', JSON.stringify(isDrawerOpen.value));
};

const selectCategory = async (categoryId: string) => {
    activeCategoryId.value = (activeCategoryId.value === categoryId) ? null : categoryId;

    await productStore.getProductsForSales({
        search_key: searchQuery.value,
        category_id: activeCategoryId.value,
    });
};

const placeOrder = async () => {
    await saleStore.saveSale();
    await productStore.getProductsForSales({
        search_key: searchQuery.value,
        category_id: activeCategoryId.value,
    });
};

watch(searchQuery, async () => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(async () => {
        await productStore.getProductsForSales({
            search_key: searchQuery.value,
            category_id: activeCategoryId.value,
        });
    }, 500);
});

onMounted(() => {
    productCategoryStore.getCategoriesForSales();
    productStore.getProductsForSales({});
});
</script>

<template>
    <Base>
        <template v-slot:topbar-content>
            <div class="flex items-center p-2 rounded-full max-w-85 md:max-w-95 gap-2 bg-gray-100 hover:border-1 hover:border-gray-400 hover:shadow-sm focus-within:border-1 focus-within:border-gray-400 focus-within:shadow-sm transition-all duration-250 ease-in-out">
                <img src="/icons/search.svg" class="p-0 h-full" alt="">
                    
                <input type="text" placeholder="Search Products" class="border-0 outline-none bg-transparent w-55 md:w-90 placeholder-gray-400" v-model="searchQuery">
                    
                <div class="flex items-center justify-center h-full mr-2">
                    <img v-if="isLoading" src="/icons/loading.svg" class="p-0 h-full animate-spin shrink-0 rounded-full" alt="">
                    
                    <button v-else-if="!isLoading && !_.isEmpty(searchQuery)" @click="searchQuery = ''" class="flex items-center justify-center h-full cursor-pointer shrink-0 rounded-full active:bg-red-200">
                        <img src="/icons/close.svg" class="p-1 h-full" alt=""></img>
                    </button>

                    <button v-else-if="!isLoading && _.isEmpty(searchQuery)" @click="console.log('Open Scanner')" class="flex items-center justify-center h-full cursor-pointer shrink-0 rounded-full active:bg-blue-200">
                        <img src="/icons/barcode_scanner.svg" class="p-1 h-full" alt=""></img>
                    </button>
                </div>
            </div>
        </template>

        <template v-slot:main-content>
            <div class="flex flex-row h-full w-full gap-3 pb-2">
                <div class="flex flex-col h-full w-full rounded-xl gap-2 lg:mr-10">
                    <!-- Loading State -->
                    <div class="flex flex-row items-center justify-center space-x-5 h-28" v-if="productCategoryStore.is_loading">
                        <div class="loader-dark h-6 w-6"></div>
                        <h2 class="text-lg font-bold"> Loading Categories... </h2>
                    </div>

                    <!-- Categories -->
                    <div class="flex flex-row gap-3 overflow-x-auto py-3" v-else>
                        <button v-for="category in productCategoryStore.categories" :key="category.id" @click="selectCategory(category.id)"
                            class="sales-product-category-button" :class="activeCategoryId === category.id
                                ? 'active-sales-product-category-button scale-105'
                                : 'default-sales-product-category-button'"
                        >
                            <div class="w-14 h-14 md:w-16 md:h-16 rounded-xl overflow-hidden mb-1 border border-gray-200 bg-gray-50">
                                <img :src="category.image || '/images/placeholder.png'" :alt="category.name" class="w-full h-full object-cover"/>
                            </div>

                            <span class="text-xs md:text-sm font-medium text-center truncate max-w-[12ch]">
                                {{ category.name }}
                            </span>
                        </button>
                    </div>

                    <!-- Product List -->
                    <div class="flex-1 bg-gray-100 overflow-y-auto mr-0 hide-scrollbar">
                        <div class="flex flex-row items-center justify-center mt-10 space-x-5" v-if="productStore.is_loading">
                            <div class="loader-dark h-6 w-6"></div> 
                            <h2 class="text-lg font-bold"> Loading Products... </h2>
                        </div>

                        <div class="flex flex-row items-center justify-center mt-10 space-x-5" v-else-if="_.isEmpty(productStore.products)">
                            <div class="h-12 w-12 flex items-center justify-center rounded-full bg-gray-200 p-2">
                                <img src="/icons/product.svg" alt="" class="overflow-hidden">
                            </div>

                            <h2 class="text-lg font-bold"> No Products Available </h2>
                        </div>

                        <div class="w-full grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 xl:grid-cols-5 gap-4">
                            <div v-for="product in productStore.products" :key="product.id" class="group flex flex-col p-4 bg-white rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ease-in-out">
                                <div class="relative flex items-center justify-center h-40 bg-gray-50 rounded-xl overflow-hidden">
                                    <img :src="product.image" alt="Product Image" class="h-full object-contain transition-transform duration-300 group-hover:scale-105"/>

                                    <div class="absolute top-2 right-2 text-xs font-semibold px-3 py-1 rounded-full shadow-sm" :class="product.stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                                        {{ product.stock > 0 ? `${product.stock} ${product.unit}/s available` : 'Out of stock' }}
                                    </div>
                                </div>

                                <div class="flex flex-col justify-between mt-4">
                                    <p class="font-semibold text-gray-800 text-center truncate">
                                        {{ product.name }}
                                    </p>

                                    <p class="text-gray-500 text-sm text-center mt-1">
                                        ₱ {{ Number(product.price).toFixed(2) }}
                                    </p>
                                </div>

                                <div class="mt-4">
                                    <button class="w-full py-2 text-sm font-medium text-white bg-blue-600 rounded-lg cursor-pointer hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed" :disabled="product.stock <= 0" @click="saleStore.addToInvoice(product)">
                                        {{ product.stock > 0 ? 'Add' : 'Unavailable' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sales-invoice-drawer" :class="isDrawerOpen ? 'w-full lg:w-[40%] xl:w-[35%]' : 'w-0'">
                    <div class="sales-invoice-drawer-header"> 
                        <div class="flex flex-row justify-between w-full px-3">
                            <div class="flex gap-4 w-full items-center overflow-hidden transition-opacity duration-300 ease-in-out lg:opacity-100" :class="isDrawerOpen ? '' : 'opacity-0'"> 
                                <div class="h-10 w-10 flex shrink-0 items-center justify-center rounded-full group-hover:shadow-sm bg-gray-200 p-1 overflow-hidden">
                                    <img src="/icons/invoice.svg" alt="" class="overflow-hidden">
                                </div>

                                <h1 class="text-nowrap overflow-hidden text-2xl font-bold"> Orders </h1> 
                            </div>

                            <div class="w-10 h-10">
                                <button @click="toggleOrderDrawer()" class="sales-invoice-drawer-header-close-btn">
                                    <span class="sales-invoice-drawer-header-close-btn-icon">
                                        ✕
                                    </span>
                                </button>
                            </div>
                        </div>

                        <button @click="toggleOrderDrawer()" class="sales-invoice-drawer-header-toggle-btn">
                            Orders
                        </button>
                    </div>

                    <!-- Order Content -->
                    <div class="flex flex-col flex-1 overflow-y-auto px-3 pt-2 space-y-3 hide-scrollbar">                            
                        <div class="flex-1 flex flex-col bg-gradient-to-b from-gray-50 to-gray-100 shadow-inner rounded-xl overflow-y-auto p-3 space-y-2 hide-scrollbar">
                            <div v-for="item in saleStore.items" :key="item.id" class="group flex items-center justify-between text-nowrap bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-3 border border-gray-100 hover:border-blue-300 hover:bg-blue-50/30">
                                <div class="flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="relative flex items-center justify-center h-16 w-16 bg-gray-50 rounded-lg overflow-hidden ring-1 ring-gray-200 group-hover:ring-blue-300 transition">
                                        <img :src="item.image" alt="Product Image" class="object-contain h-full w-full transition-transform duration-300 group-hover:scale-110"/>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex flex-col justify-between">
                                        <div class="text-sm font-semibold text-gray-800 truncate max-w-[160px] leading-tight">
                                            {{ item.name }}
                                        </div>

                                        <div class="flex items-center space-x-2 mt-1">
                                            <button @click="saleStore.updateQuantity(item.id, -1)" class="flex items-center justify-center h-7 w-7 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-bold transition cursor-pointer">
                                                -
                                            </button>

                                            <input type="text" class="w-14 h-8 text-center text-sm font-semibold text-gray-800 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                                                @input="saleStore.updateSubtotal(item.id)" v-input-mask="_.merge(quantityInputMask, {max: item.stock})" 
                                                v-model.number="item.quantity"/>

                                            <button @click="saleStore.updateQuantity(item.id, 1)" class="flex items-center justify-center h-7 w-7 rounded-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold transition cursor-pointer">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Section -->
                                <div class="text-right pr-2">
                                    <button class="btn-danger w-6 h-6 p-1 transition ml-auto" title="Remove item" @click="saleStore.removeFromCart(item.id)">
                                        <img src="/icons/delete.svg" class="p-0 m-0 h-full w-full" alt="">
                                    </button>

                                    <div class="text-lg font-bold text-blue-600 leading-tight">
                                        ₱ {{ item.subtotal }}
                                    </div>
                                        
                                    <div class="text-xs text-gray-500 italic">
                                        ₱ {{ item.price }} ea
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Totals -->
                        <div class="payment-totals-container">
                            <div class="payment-totals-header-section">
                                <h2> Pricing Summary </h2>
                            </div>

                            <div class="flex flex-col justify-center">
                                <div class="flex flex-row justify-between">
                                    <p class="text-sm font-medium text-gray-500 px-2 text-nowrap">Subtotal</p>
                                    <p class="text-sm font-medium text-gray-500 px-2 text-nowrap"> ₱ {{ (saleStore.subtotal).toFixed(2) }} </p>
                                </div>

                                <div class="flex flex-row justify-between">
                                    <p class="text-sm font-medium text-gray-500 px-2 text-nowrap">Tax</p>
                                    <p class="text-sm font-medium text-gray-500 px-2 text-nowrap"> ₱ {{ (saleStore.taxableAmount).toFixed(2) }} </p>
                                </div>

                                <div class="flex flex-row justify-between">
                                    <p class="text-sm font-medium text-gray-500 px-2 text-nowrap">Discount</p>
                                    <p class="text-sm font-medium text-gray-500 px-2 text-nowrap"> ₱ {{ (saleStore.discountedAmount).toFixed(2) }} </p>
                                </div>
                            </div>

                            <div class="border-t-2 border-gray-100 mt-2 px-2 pt-1">
                                <div class="flex flex-row justify-between">
                                    <h2 class="text-md font-bold text-nowrap">Total</h2>
                                    <h2 class="text-md font-bold text-nowrap"> ₱ {{ (saleStore.total).toFixed(2) }} </h2>
                                </div>
                            </div>
                        </div>

                        <!-- Payment -->
                        <div class="payment-totals-container">
                            <div class="payment-totals-header-section">
                                <h2> Payment </h2>
                            </div>

                            <div class="flex flex-row justify-between items-center px-2">
                                <label class="text-sm font-medium text-gray-500 text-nowrap">
                                    Cash Amount
                                </label>
                                    
                                <div class="flex flex-row space-x-3">
                                    <span> ₱ </span>

                                    <input type="text" class="payment-cash-input" 
                                        v-model.number="saleStore.cashAmount" 
                                        v-input-mask="cashAmountInputMask"/>
                                </div>
                            </div>

                            <!-- Change Display -->
                            <div class="flex flex-row justify-between px-2 mt-1">
                                <h2 class="text-md font-bold text-nowrap"> Change </h2>
                                
                                <p class="text-md font-bold text-green-600 text-nowrap">
                                    ₱ {{ saleStore.change.toFixed(2) }}
                                </p>
                            </div>
                        </div>

                        <!-- Place Order -->
                        <div class="flex flex-col justify-center items-center">
                            <button class="btn-primary w-full text-nowrap" @click="placeOrder" :disabled="_.isEmpty(saleStore.items) || saleStore.cashAmount <= saleStore.total">
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>

                <button @click="toggleOrderDrawer()" class="sales-drawer-toggle">
                    <transition name="fade" mode="out-in">
                        <span key="cart" class="sales-drawer-toggle-icon">🛒</span>
                    </transition>

                    <!-- Badge for item count -->
                    <span class="sales-drawer-toggle-badge">
                        {{ saleStore.totalQuantity }}
                    </span>
                </button>
            </div>
        </template>
    </Base>
</template>

<style scoped>
@reference "tailwindcss";

.sales-product-category-button {
  @apply cursor-pointer transition duration-300 ease-in-out text-nowrap flex flex-col items-center justify-center min-w-[90px] md:min-w-[110px] rounded-xl p-2 hover:-translate-y-1;
}

.default-sales-product-category-button {
  @apply bg-white text-gray-700 shadow-sm hover:shadow-md hover:bg-blue-50;
}

.active-sales-product-category-button {
  @apply bg-blue-500 text-white shadow-md hover:shadow-lg;
}

.sales-drawer-toggle {
    @apply fixed bottom-6 right-6 flex items-center justify-center lg:hidden text-white rounded-full shadow-xl cursor-pointer transition-all duration-500 ease-in-out h-14 w-14 bg-blue-500 hover:bg-blue-400 rotate-0;
}

.sales-drawer-toggle-icon {
    @apply text-3xl transform transition-transform duration-500;
}

.sales-drawer-toggle-badge {
    @apply absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center shadow;
}

.sales-invoice-drawer {
    @apply flex flex-col shadow-sm px-0 pt-5 pb-3 transition-all duration-700 ease-in-out bg-white fixed rounded-md lg:static top-0 right-0 h-full z-50;
}

.sales-invoice-drawer-header {
    @apply relative flex justify-between mb-2 h-10 items-center;
}

.sales-invoice-drawer-header-toggle-btn {
    @apply absolute text-xl font-bold cursor-pointer border-t-4 border-transparent transition-all duration-250 ease-in-out -left-15 top-6 h-fit w-fit items-center justify-center shrink-0 rounded-t-md hover:border-blue-500 bg-white hidden lg:flex rotate-270 py-1 px-2;
}

.sales-invoice-drawer-header-close-btn {
    @apply block leading-none items-center justify-center text-center text-black rounded-full lg:hidden cursor-pointer transition-all duration-500 ease-in-out h-10 w-10 bg-gray-50 hover:bg-red-500 hover:text-white rotate-180;
}

.sales-invoice-drawer-header-close-btn-icon {
    @apply h-full w-full text-2xl font-bold transform transition-transform duration-500 text-nowrap;
}

.payment-totals-container {
    @apply flex flex-col bg-white shadow-inner py-3 rounded-md;
}

.payment-totals-header-section {
    @apply border-b-2 border-gray-100 mb-2 px-2 pb-1 justify-center;
}

.payment-totals-header-section h2 {
    @apply text-base font-semibold text-nowrap;
}

.payment-cash-input {
    @apply w-30 text-right text-sm font-semibold text-gray-800 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-2 py-1;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: scale(0.8);
}

/* Remove spinner for Chrome, Edge, Safari */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Remove spinner for Firefox */
input[type="number"] {
  -moz-appearance: textfield;
}
</style>