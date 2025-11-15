import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';

export const useProductSalesStore = defineStore('product-sales-store', {
    state: () => ({
        productSales: [],
        is_loading: false,
    }),

    getters: {

    },

    actions: {
        async fetchProductSalesBreakdownList(params = {}) {
            try {
                this.productSales = [];
                this.is_loading = true;
                const response = await axios.post('/api/product_sales/get_product_sales_breakdown_list', params);
                this.productSales = response.data.data;
            } catch (error) {
                console.error("Error fetching product sales:", error);
            } finally {
                this.is_loading = false;
            }
        }
    }
});

