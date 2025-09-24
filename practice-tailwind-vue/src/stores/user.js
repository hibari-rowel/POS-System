import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import useVuelidate from "@vuelidate/core"
import { fireToast } from "@/lib/toast";

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
                for (const key in v$.value.$errors) {
                    let field = v$.value.$errors[key].$property;
                    this.errors[field] = [v$.value.$errors[key].$message];
                }

                return;
            }

            try {
                const response = await axios.post('/api/users/create', data);
                fireToast("success", 'User created successfully.');
                this.router.push('/users');
            } catch (error) {
                let errors = error.response?.data?.errors || {};
                const mergedErrors = _.flatten(_.values(errors)).join(' ');
                this.errors = errors;

                fireToast("error", mergedErrors);
            }
        },
        async updateUser(data, rules, id) {
            this.resetErrors();

            const v$ = useVuelidate(rules, data);
            v$.value.$touch();

            if (v$.value.$invalid) {
                for (const key in v$.value.$errors) {
                    let field = v$.value.$errors[key].$property;
                    this.errors[field] = [v$.value.$errors[key].$message];
                }

                return;
            }

            try {
                const response = await axios.post(`/api/users/update/${id}`, data);
                fireToast("success", 'User created successfully.');
                this.router.push('/users');
            } catch (error) {
                let errors = error.response?.data?.errors || {};
                const mergedErrors = _.flatten(_.values(errors)).join(' ');
                this.errors = errors;

                fireToast("error", mergedErrors);
            }
        },
        async getUser(data) {

        },
        async getUsers(data) {

        },
        resetErrors() {
            this.errors = {};
        },
        cleanErrors(field) {
            this.errors[field] = null
        }
    },
});