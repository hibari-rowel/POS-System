import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import Swal from 'sweetalert2';
import useVuelidate from "@vuelidate/core"

export const useStockStore = defineStore('stock-store', {
    state: () => ({
        errors: {},
        stocks: {},
        total_records: 0,
    }),

    getters: {

    },

    actions: {
        async createStock(data, rules) {
            this.resetErrors();

            const v$ = useVuelidate(rules, data);
            v$.value.$touch();

            if (v$.value.$invalid) {
                this.assignFrontEndValidationErrors(v$.value.$errors);
                return false;
            }

            try {
                data.product_id = data.product.id;

                const response = await axios.post('/api/stocks/create', data);

                return true;
            } catch (error) {
                this.errors = error.response?.data?.errors || {};
                return false;
            }
        },
        async updateStock(data, rules, id) {
            this.resetErrors();

            const v$ = useVuelidate(rules, data);
            v$.value.$touch();
            if (v$.value.$invalid) {
                this.assignFrontEndValidationErrors(v$.value.$errors);
                return false;
            }

            try {
                data.product_id = data.product.id;

                const formData = new FormData();
                formData.append('_method', "PUT");
                for (const key in data) {
                    if (key === 'image' && !(data[key] instanceof File)) {
                        continue;
                    }

                    if (data[key] !== null && data[key] !== undefined) {
                        formData.append(key, data[key]);
                    }
                }

                const response = await axios.post(`/api/stocks/update/${id}`, formData);

                return true;
            } catch (error) {
                this.errors = error.response?.data?.errors || {};
                return false;
            }
        },
        async getStocks(data) {
            try {
                const response = await axios.post('/api/stocks/get_list', data);
                let responseData = response.data;
                this.stocks = responseData.data;
                this.total_records = responseData.total_records;
            } catch (error) {
                console.error("Error fetching products:", error.response.data.message);
                this.stocks = {};
            }
        },
        async getStock(id) {
            try {
                const response = await axios.get(`/api/stocks/get/${id}`);
                return response.data.data;
            } catch (error) {
                console.error("Error fetching product:", error.response.data.message);
                return null;
            }
        },
        async deleteStock(id) {
            try {
                await axios.delete(`/api/stocks/destroy/${id}`);
                return true;
            } catch (error) {
                console.error("Error deleting product:", error.response.data.message);
                return false;
            }
        },
        assignFrontEndValidationErrors(errors) {
            for (const key in errors) {
                let field = errors[key].$property;
                this.errors[field] = [errors[key].$message];
            }
        },
        resetErrors() {
            this.errors = {};
        },
        cleanErrors(field) {
            this.errors[field] = null
        },
    },
});