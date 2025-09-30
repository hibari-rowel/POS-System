import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import useVuelidate from "@vuelidate/core"

export const useUserStore = defineStore('user-store', {
    state: () => ({
        user: null,
        users: {},
        errors: {},
        is_loading: false,
        total_records: 0,
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
            try {
                const response = await axios.post('/api/users/get_list', data);
                let responseData = response.data;
                this.users = responseData.data;
                this.total_records = responseData.total_records;
            } catch (error) {
                console.error("Error fetching users:", error.response.data.message);
                this.users = {};
            }
        },
        async deleteUser(id) {
            try {
                await axios.delete(`/api/users/destroy/${id}`);
                return true;
            } catch (error) {
                console.error("Error deleting user:", error.response.data.message);
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