import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import useVuelidate from "@vuelidate/core"

export const useUserStore = defineStore('user-store', {
    state: () => ({
        user: {},
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
                const response = await axios.post('/api/users/create', data, {
                    headers: { "Content-Type": "multipart/form-data", },
                });

                return true;
            } catch (error) {
                this.errors = error.response?.data?.errors || {};
                return false;
            }
        },
        async updateUser(data, rules, id) {
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
                formData.append('user_id', id);
                formData.append('_method', "PUT");
                for (const key in data) {
                    if (key === 'image' && !(data[key] instanceof File)) {
                        continue;
                    }

                    if (data[key] !== null && data[key] !== undefined) {
                        formData.append(key, data[key]);
                    }
                }

                const response = await axios.post(`/api/users/update/${id}`, formData, {
                    headers: { "Content-Type": "multipart/form-data", },
                });

                return true;
            } catch (error) {
                this.errors = error.response?.data?.errors || {};
                return false;
            }
        },
        async getUser(id) {
            try {
                const response = await axios.get(`/api/users/get/${id}`);
                return response.data.user;
            } catch (error) {
                console.error("Error fetching user:", error.response.data.message);
                return null;
            }
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