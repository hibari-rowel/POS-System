import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import useVuelidate from "@vuelidate/core"

export const useUserStore = defineStore('user-store', {
    state: () => ({
        user: null,
        users: {},
        errors: {},
    }),

    getters: {

    },

    actions: {
        async createUser(data, rules) {
            this.resetErrors();

            const v$ = useVuelidate(rules, data);
            v$.value.$touch();

            if (v$.value.$invalid) {
                this.assignFrontEndValidationErrors(v$.value.$errors);
                return false;
            }

            try {
                const response = await axios.post('/api/users/create', data);
                return true;
            } catch (error) {
                this.errors = error.response?.data?.errors || {};
                return false;
            }
        },
        async updateUser(data, rules, id) {
            this.resetErrors();

            const v$ = useVuelidate(rules, data);
            v$.value.$touch();

            if (v$.value.$invalid) {
                this.assignFrontEndValidationErrors(v$.value.$errors);
                return false;
            }

            try {
                const response = await axios.post(`/api/users/update/${id}`, data);
                return true;
            } catch (error) {
                this.errors = error.response?.data?.errors || {};
                return false;
            }
        },
        async getUser(data) {

        },
        async getUsers(data) {

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