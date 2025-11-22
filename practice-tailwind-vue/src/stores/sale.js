import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _, { round } from 'lodash';
import Swal from 'sweetalert2';
import { fireToast } from "@/lib/toast";

export const useSaleStore = defineStore('sale-store', {
    state: () => ({
        errors: {},
        sales: {},
        total_records: 0,
        items: [],
        tax: 0,
        discount: 0,
        cashAmount: 0,
    }),

    getters: {
        totalQuantity: (state) => state.items.length,
        subtotal: (state) => state.items.reduce((sum, item) => sum + item.subtotal, 0),
        taxableAmount: (state) => state.subtotal * state.tax,
        discountedAmount: (state) => (state.subtotal * state.discount) * -1,
        total: (state) => state.subtotal + state.taxableAmount + state.discountedAmount,
        change: (state) => state.cashAmount > state.total ? round((state.cashAmount - state.total), 2) : 0,
    },

    actions: {
        addToInvoice(product) {
            const existing = this.items.find(item => item.id === product.id);

            if (existing) {
                if (existing.quantity < product.stock) {
                    existing.quantity += 1;
                    existing.subtotal = round((existing.quantity * existing.price), 2);
                }
            } else {
                this.items.push({ ...product, quantity: 1, subtotal: round(product.price, 2) });
            }
        },

        updateSubtotal(id) {
            const item = this.items.find(item => item.id === id);

            if (item) {
                item.subtotal = round((item.quantity * item.price), 2);
            }
        },

        updateQuantity(id, addedQuantity) {
            const item = this.items.find(item => item.id === id);

            if (item) {
                let newQuantity = (addedQuantity + item.quantity);

                item.quantity = (newQuantity <= 0 || newQuantity > item.stock) ? item.quantity : newQuantity;
                item.subtotal = round((item.quantity * item.price), 2);
            }
        },

        removeFromCart(id) {
            this.items = this.items.filter(item => item.id !== id)
        },

        async saveSale() {
            if (this.items.length === 0) {
                fireToast("warning", 'Order is empty. Please add items to the order before saving.');
                return;
            }

            if (this.cashAmount < this.total) {
                fireToast("warning", 'Insufficient cash amount. Please enter a valid cash amount.');
                return;
            }

            let confirmation = Swal.mixin();
            let loading = Swal.mixin();

            try {
                let result = await confirmation.fire({
                    icon: "question",
                    title: "Confirm order?",
                    showCancelButton: true,
                    confirmButtonText: "Save",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: "btn-primary mx-10",
                        cancelButton: "btn-danger mx-10",
                    }
                });

                if (!result.isConfirmed) {
                    return;
                }

                confirmation.close();

                loading = loading.fire({
                    title: 'Saving...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                const response = await axios.post('/api/sales/create', {
                    items: this.items,
                    subtotal_amount: this.subtotal,
                    tax_amount: this.taxableAmount,
                    discount_amount: this.discountedAmount,
                    total_amount: this.total,
                    cash_amount: this.cashAmount,
                    change_amount: this.change,
                });

                loading.close();

                // print receipt here
                fireToast("success", 'Sale saved successfully.');
                this.items = [];
                this.cashAmount = 0;
            } catch (error) {
                fireToast("error", 'Somthing went wrong. Please contact support for assistance.');
            }
        },

        async getSales(data) {
            try {
                const response = await axios.post('/api/sales/get_list', data);
                let responseData = response.data;
                this.sales = responseData.data;
                this.total_records = responseData.total_records;
            } catch (error) {
                console.error("Error fetching sales:", error.response.data.message);
                this.sales = {};
            }
        },
    },
});