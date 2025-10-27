import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _, { round } from 'lodash';
import Swal from 'sweetalert2';

export const useSaleStore = defineStore('sale-store', {
    state: () => ({
        items: [],
        tax: 0.02,
        discount: 0.05
    }),

    getters: {
        totalQuantity: (state) => state.items.length,
        subtotal: (state) => state.items.reduce((sum, item) => sum + item.subtotal, 0),
        taxableAmount: (state) => state.subtotal * state.tax,
        discountedAmount: (state) => (state.subtotal * state.discount) * -1,
        total: (state) => state.subtotal + state.taxableAmount + state.discountedAmount,
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

                item.quantity = (newQuantity <= 0) ? item.quantity : newQuantity;
                item.subtotal = round((item.quantity * item.price), 2);
            }
        },

        removeFromCart(id) {
            this.items = this.items.filter(item => item.id !== id)
        },

        async saveSale(saleData) {
            const response = await axios.post('/api/sales/create', saleData);

        },
    },
});