import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import useVuelidate from "@vuelidate/core"
import { fireToast } from "@/lib/toast";

export const useAuthStore = defineStore('auth-store', {
    state: () => ({
        user: null,
        errors: {},
        is_loading: false,
    }),

    getters: {
        isAuthenticated: (state) => !_.isEmpty(state.user),
    },

    actions: {
        async login(data, rules) {
            this.is_loading = true;
            this.resetErrors();

            const v$ = useVuelidate(rules, data);
            v$.value.$touch();

            if (v$.value.$invalid) {
                for (const key in v$.value.$errors) {
                    let field = v$.value.$errors[key].$property;
                    this.errors[field] = [v$.value.$errors[key].$message];
                }

                this.is_loading = false;
                return;
            }

            await axios.get('/sanctum/csrf-cookie');

            try {
                const response = await axios.post('/api/login', data);
                fireToast("success", 'Logged in successfully.');
                this.router.push('/dashboard');
            } catch (error) {
                let errors = error.response?.data?.errors || {};
                const mergedErrors = _.flatten(_.values(errors)).join(' ');
                this.errors = errors;

                fireToast("error", mergedErrors);

                this.is_loading = false;
            }

            this.is_loading = false;
        },
        async logout() {
            try {
                const response = await axios.post('/api/logout');
                this.user = null;
                this.resetErrors();

                fireToast("success", 'Logged out successfully.');

                this.router.push('/');
            } catch (error) {
                fireToast("error", "Something went wrong. Please contact the administration.");
            }
        },
        async fetchUser() {
            if (_.isEmpty(this.user)) {
                try {
                    const response = await axios.get('/api/fetch_user');
                    this.user = response.data.user;
                } catch (error) {
                    this.user = null;
                }
            }
        },
        resetErrors() {
            this.errors = {};
        },
        cleanErrors(field) {
            this.errors[field] = null
        }
    }
});