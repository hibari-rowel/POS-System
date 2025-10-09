import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import useVuelidate from "@vuelidate/core"

export const useProductCategoryStore = defineStore('product-category-store', {
    state: () => ({
        productCategories: {},
        errors: {},
        total_records: 0,
    }),

    getters: {

    },

    actions: {
        async createCategory(data, rules) {
            this.resetErrors();

            const v$ = useVuelidate(rules, data);
            v$.value.$touch();

            if (v$.value.$invalid) {
                this.assignFrontEndValidationErrors(v$.value.$errors);
                return false;
            }

            try {
                const response = await axios.post('/api/product_categories/create', data, {
                    headers: { "Content-Type": "multipart/form-data", },
                });

                return true;
            } catch (error) {
                this.errors = error.response?.data?.errors || {};
                return false;
            }
        },
        async updateCategory(data, rules, id) {
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

                const response = await axios.post(`/api/product_categories/update/${id}`, formData, {
                    headers: { "Content-Type": "multipart/form-data", },
                });

                return true;
            } catch (error) {
                this.errors = error.response?.data?.errors || {};
                return false;
            }
        },
        async getCategories(data) {
            try {
                const response = await axios.post('/api/product_categories/get_list', data);
                let responseData = response.data;
                this.productCategories = responseData.data;
                this.total_records = responseData.total_records;
            } catch (error) {
                console.error("Error fetching categories:", error.response.data.message);
                this.users = {};
            }
        },
        async getCategory(id) {
            try {
                const response = await axios.get(`/api/product_categories/get/${id}`);
                return response.data.product_category;
            } catch (error) {
                console.error("Error fetching user:", error.response.data.message);
                return null;
            }
        },
        async deleteCategory(id) {
            try {
                await axios.delete(`/api/product_categories/destroy/${id}`);
                return true;
            } catch (error) {
                console.error("Error deleting category:", error.response.data.message);
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