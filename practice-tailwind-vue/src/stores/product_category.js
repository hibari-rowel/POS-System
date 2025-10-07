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

        },
        async deleteCategory(id) {

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