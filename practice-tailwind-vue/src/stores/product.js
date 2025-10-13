import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import Swal from 'sweetalert2';
import useVuelidate from "@vuelidate/core"

export const useProductStore = defineStore('product-store', {
    state: () => ({
        errors: {},
        products: {},
        total_records: 0,
    }),

    getters: {

    },

    actions: {
        async createProduct(data, rules) {
            this.resetErrors();

            const v$ = useVuelidate(rules, data);
            v$.value.$touch();

            if (v$.value.$invalid) {
                this.assignFrontEndValidationErrors(v$.value.$errors);
                return false;
            }

            try {
                data.product_category_id = data.product_category.id;

                const response = await axios.post('/api/products/create', data, {
                    headers: { "Content-Type": "multipart/form-data", },
                });

                return true;
            } catch (error) {
                this.errors = error.response?.data?.errors || {};
                return false;
            }
        },
        async updateProduct(data, rules, id) {
            this.resetErrors();
            if (!(data.image instanceof File)) {
                data.image = null;
            }

            const v$ = useVuelidate(rules, data);
            v$.value.$touch();
            if (v$.value.$invalid) {
                this.assignFrontEndValidationErrors(v$.value.$errors);
                return false;
            }

            try {
                data.product_category_id = data.product_category.id;

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

                const response = await axios.post(`/api/products/update/${id}`, formData, {
                    headers: { "Content-Type": "multipart/form-data", },
                });

                return true;
            } catch (error) {
                this.errors = error.response?.data?.errors || {};
                return false;
            }
        },
        async getProducts(data) {
            try {
                const response = await axios.post('/api/products/get_list', data);
                let responseData = response.data;
                this.products = responseData.data;
                this.total_records = responseData.total_records;
            } catch (error) {
                console.error("Error fetching products:", error.response.data.message);
                this.products = {};
            }
        },
        async getProduct(id) {
            try {
                const response = await axios.get(`/api/products/get/${id}`);
                return response.data.product;
            } catch (error) {
                console.error("Error fetching product:", error.response.data.message);
                return null;
            }
        },
        async deleteProduct(id) {
            try {
                await axios.delete(`/api/products/destroy/${id}`);
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