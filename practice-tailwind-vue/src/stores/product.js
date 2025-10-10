import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import Swal from 'sweetalert2';

export const useProductStore = defineStore('product-store', {
    state: () => ({
        errors: {},
    }),

    getters: {

    },

    actions: {
        resetErrors() {
            this.errors = {};
        },
        cleanErrors(field) {
            this.errors[field] = null
        },
    },
});