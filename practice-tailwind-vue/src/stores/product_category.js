import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import Swal from 'sweetalert2';

export const useProductCategoryStore = defineStore('product-category-store', {
    state: () => ({
        productCategories: {},
        errors: {},
        total_records: 0,
    }),

    getters: {

    },

    actions: {
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