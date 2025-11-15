<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import _ from 'lodash';
import { useSaleStore } from "@/stores/sale.js";
import { useProductSalesStore } from "@/stores/product_sales.js";
import DateFormatter from "@/lib/date.ts";

import Base from '@/components/BaseComponents/Base.vue';
import Modal from '@/components/BaseComponents/Modal.vue';

let searchTimeout : ReturnType<typeof setTimeout>;
const saleStore = useSaleStore();
const productSalesStore = useProductSalesStore();

const isLoading = ref(false);
const recordsPerPage = ref(10);
const searchQuery = ref("");
const startIndex = ref(0);
const currentPage = ref(1);
const endIndex = computed(() => {
    let end = startIndex.value + recordsPerPage.value;

    return (end > saleStore.total_records) ? saleStore.total_records : end;
});

const totalPages = computed(() => {
    return Math.ceil(saleStore.total_records / recordsPerPage.value);
});

const fetchSales = async () => {
    isLoading.value = true;

    await saleStore.getSales({
        start: startIndex.value,
        records_per_page: recordsPerPage.value,
        search: searchQuery.value,
    });

    isLoading.value = false;
}

const clickPaginationNavigation = (offset: number) => {
    startIndex.value += offset; 
    currentPage.value = Math.ceil((startIndex.value + 1) / recordsPerPage.value);

    fetchSales();
}

watch(recordsPerPage, async () => {
    startIndex.value = 0;
    currentPage.value = 1;

    await fetchSales();
});

watch(searchQuery, async () => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(async () => {
        startIndex.value = 0;
        currentPage.value = 1;

        await fetchSales();
    }, 300);
});

const isModalOpen = ref(false);
const selectedItem = ref({});
const toggleModal = (isOpen: boolean, item: any = {}) => {
    isModalOpen.value = isOpen;

    if (isOpen) {
        selectedItem.value = item;
        productSalesStore.fetchProductSalesBreakdownList({ sale_id: item.id });
    }
}

onMounted(async () => {
    await fetchSales();
});
</script>

<template>
    <Base>
        <template v-slot:topbar-content>
            <span class="text-2xl font-bold"> Sales History </span>
        </template>
        
        <template v-slot:main-content>
            <div class="flex flex-col h-auto mb-2 bg-white rounded-lg shadow">
                <div class="flex flex-col sm:flex-row items-center sm:justify-between h-auto border-b-2 border-gray-50 p-2 mb-1 shadow-sm">
                    <div class="flex items-center w-full mb-0 sm:max-w-100">
                        <div class="flex items-center p-2 rounded-md w-full gap-2 bg-gray-100 border-1 border-gray-300 hover:border-gray-400 hover:shadow-sm focus-within:border-gray-400 focus-within:shadow-sm transition-all duration-250 ease-in-out">
                            <img src="/icons/search.svg" class="p-0 h-full" alt="">

                            <input type="text" class="border-0 outline-none bg-transparent h-full w-full placeholder-gray-400" placeholder="Search Invoice Number" v-model="searchQuery" />
                        </div>
                    </div>

                    <div class="flex items-center justify-center mt-3 sm:mt-0 gap-5">
                        <span> Entries per Page: </span>

                        <select v-model="recordsPerPage" class="border-1 border-gray-300 rounded-md p-1 hover:border-gray-400 hover:shadow-sm focus:border-gray-400 focus:shadow-sm transition-all duration-250 ease-in-out">
                            <option :value="10"> 10 </option>
                            <option :value="15"> 15 </option>
                            <option :value="20"> 20 </option>
                        </select> 
                    </div>
                </div>

                <div class="flex flex-col items-center h-full w-full">
                    <table class="min-w-full text-sm text-left text-gray-600">
                        <thead class="bg-gray-50 text-gray-700 uppercase text-sm border-b-2 border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3"> Invoice Number </th>
                                <th scope="col" class="px-6 py-3"> Sale Date </th>
                                <th scope="col" class="px-6 py-3"> Total Amount </th>
                                <th scope="col" class="px-6 py-3"> Cash Amount </th>
                                <th scope="col" class="px-6 py-3"> Change </th>
                                <th scope="col" class="px-6 py-3"> Created At </th>
                            </tr>
                        </thead>

                        <tbody v-if="isLoading">
                            <tr class="border-b-1 border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-3 text-center items-center justify-center w-full" colspan="6">
                                    <span class="inline-flex gap-6 items-center justify-center text-lg">
                                        <div class="loader-dark h-6 w-6"></div> Loading...
                                    </span>
                                </td>
                            </tr>
                        </tbody>

                        <tbody v-else-if="_.isEmpty(saleStore.sales)">
                            <tr class="border-b-1 border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-3 text-center items-center justify-center w-full text-base font-bold" colspan="6">
                                    No Invoice Found.
                                </td>
                            </tr>
                        </tbody>

                        <tbody v-else>
                            <tr v-for="sale in saleStore.sales" :key="sale.id" class="border-b-1 border-gray-200 hover:bg-gray-50 transition">
                                <td class="flex gap-3 items-center px-6 py-3">
                                    <button class="btn-default w-8 h-8 p-1" @click="toggleModal(true, sale)">
                                        <img src="/icons/details.svg" class="p-0 h-[95%] w-[95%]" alt="">
                                    </button>

                                    <div class="font-medium text-gray-900"> {{ sale.invoice_number }} </div>
                                </td>
                                <td class="px-6 py-3 capitalize"> {{ DateFormatter.toReadable(sale.sale_date) }} </td>
                                <td class="px-6 py-3 capitalize"> ₱ {{ sale.total_amount }} </td>
                                <td class="px-6 py-3 capitalize"> ₱ {{ sale.cash_amount }} </td>
                                <td class="px-6 py-3 capitalize"> ₱ {{ sale.change_amount }} </td>
                                <td class="px-6 py-3 capitalize"> {{ DateFormatter.toReadable(sale.created_at) }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:justify-between h-full w-full p-4">
                    <span class="text-gray-600">
                        Showing {{ startIndex + 1 }} to {{ endIndex }} of {{ saleStore.total_records }} entries
                    </span>

                    <div class="flex flex-row items-center gap-2">
                        <button class="px-3 py-1 border rounded disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed" @click="clickPaginationNavigation(-recordsPerPage)" :disabled="currentPage === 1">
                            Prev
                        </button>

                        {{ currentPage }} / {{ _.isEmpty(saleStore.sales) ? 1 : totalPages }}

                        <button class="px-3 py-1 border rounded disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed" @click="clickPaginationNavigation(recordsPerPage)" :disabled="currentPage === totalPages || _.isEmpty(saleStore.sales)">
                            Next
                        </button>
                    </div>
                </div>
            </div>

            
            <Modal :show="isModalOpen" :size="'max-w-5xl'" @close="toggleModal(false)">
                <template v-slot:header>
                    <span class="text-2xl font-bold"> Breakdown </span>
                </template>

                <template v-slot:body>
                    <div class="flex flex-col space-y-3">
                        <div class="max-h-90 overflow-y-scroll hide-scrollbar shadow-sm rounded-md snap-y scroll-pt-[48px] border border-gray-300">
                            <table class="min-w-full text-sm text-left text-gray-600">
                                <thead class="bg-red-100 text-gray-700 uppercase text-sm border-b-2 border-red-100 top-0 sticky">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 w-[45%]"> Product name </th>
                                        <th scope="col" class="px-6 py-3 w-[20%] text-end"> Price </th>
                                        <th scope="col" class="px-6 py-3 w-[20%] text-end"> Quantity </th>
                                        <th scope="col" class="px-6 py-3 w-[15%] text-end"> Subtotal </th>
                                    </tr>
                                </thead>

                                <tbody v-if="productSalesStore.is_loading">
                                    <tr class="border-b-1 border-gray-200 hover:bg-gray-50 transition">
                                        <td class="px-6 py-3 text-center items-center justify-center w-full" colspan="4">
                                            <span class="inline-flex gap-6 items-center justify-center text-lg">
                                                <div class="loader-dark h-6 w-6"></div> Loading...
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>

                                <tbody v-else-if="_.isEmpty(productSalesStore.productSales)">
                                    <tr class="border-b-1 border-gray-200 hover:bg-gray-50 transition">
                                        <td class="px-6 py-3 text-center items-center justify-center w-full text-base font-bold" colspan="4">
                                            No Product Found.
                                        </td>
                                    </tr>
                                </tbody>

                                <tbody v-else>
                                    <tr v-for="productSale in productSalesStore.productSales" :key="productSale.id" class="border-b-1 border-gray-200 bg-gray-50 hover:bg-gray-100 transition snap-start">
                                        <td class="px-6 py-3 capitalize font-medium text-gray-900"> {{ productSale.product_name }} </td>
                                        <td class="px-6 py-3 capitalize text-end"> ₱ {{ productSale.price }} / {{ productSale.unit }} </td>
                                        <td class="px-6 py-3 capitalize text-end"> {{ productSale.quantity }} {{ productSale.unit }}s </td>
                                        <td class="px-6 py-3 capitalize text-end"> ₱ {{ productSale.subtotal }} </td>
                                    </tr>
                                </tbody>

                                <tbody v-if="!productSalesStore.is_loading" class="bg-red-100 text-gray-700 uppercase text-sm border-t-2 border-red-100 bottom-0 sticky">
                                    <tr v-if="selectedItem.tax_amount !== '0.00' || selectedItem.discount_amount !== '0.00'" class="border-t border-gray-500">
                                        <th colspan="3" class="px-6 py-1 uppercase text-sm font-medium text-gray-900 text-end"> 
                                            Gross Total: 
                                        </th>
                                        
                                        <td class="px-6 py-1 capitalize text-end">
                                            ₱ {{ selectedItem?.subtotal_amount ? selectedItem.subtotal_amount : '0.00' }}
                                        </td>
                                    </tr>

                                    <tr v-if="selectedItem.discount_amount !== '0.00'">
                                        <th colspan="3" class="px-6 py-1 uppercase text-sm font-medium text-gray-900 text-end"> 
                                            (Less) Discount: 
                                        </th>

                                        <td class="px-6 py-1 capitalize text-end"> 
                                            ₱ {{ selectedItem?.discount_amount ? selectedItem.discount_amount : '0.00' }}
                                        </td>
                                    </tr>

                                    <tr v-if="selectedItem.tax_amount !== '0.00'">
                                        <th colspan="3" class="px-6 py-1 uppercase text-sm font-medium text-gray-900 text-end"> 
                                            (Addt'l.) Tax:
                                        </th>

                                        <td class="px-6 py-1 capitalize text-end"> 
                                            ₱ {{ selectedItem?.tax_amount ? selectedItem.tax_amount : '0.00' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="px-6 py-1 uppercase text-base text-gray-900 text-end"> 
                                            Grand Total: 
                                        </th>

                                        <td class="px-6 py-1 capitalize font-semibold text-gray-900 text-end">
                                            ₱ {{ selectedItem?.total_amount ? selectedItem.total_amount : '0.00' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="px-6 py-1 uppercase text-sm font-medium text-gray-900 text-end"> 
                                            Payment: 
                                        </th>

                                        <td class="px-6 py-1 capitalize text-end"> 
                                            ₱ {{ selectedItem?.cash_amount ? selectedItem.cash_amount : '0.00' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="px-6 py-1 uppercase text-base text-gray-900 text-end"> 
                                            Change: 
                                        </th>
                                        
                                        <td class="px-6 py-1 capitalize font-semibold text-gray-900 text-end"> 
                                            ₱ {{ selectedItem?.change_amount ? selectedItem.change_amount : '0.00' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>

                <template v-slot:footer>
                    <div class="flex flex-row gap-3">
                        <button class="btn-danger" @click="toggleModal(false)"> Close </button>
                    </div>
                </template>
            </Modal>
        </template>
    </Base>
</template>

<style scoped></style>