import { helpers, required, minLength, maxLength } from "@vuelidate/validators";
import _ from 'lodash';

const validPrice = helpers.withMessage(
    "Value must be greater than 0",
    (value) => {
        if (!value) return false
        const numericValue = parseFloat(String(value).replace(/[₱,\s,]/g, "")) || 0
        return numericValue > 0
    }
)

export function stockValidation(form) {
    return {
        supplier_name: {
            required: helpers.withMessage("Supplier Name is required", required),
        },
        product: {
            required: helpers.withMessage("Product is required", required),
        },
        price: {
            required: helpers.withMessage("Price is required", required),
            validPrice
        },
        quantity: {
            required: helpers.withMessage("Quantity is required", required),
            validPrice
        },
        unit: {
            required: helpers.withMessage("Unit is required", required),
        },
        subtotal: {
            required: helpers.withMessage("Total is required", required),
            validPrice
        },
        stock_date: {
            required: helpers.withMessage("Stock Date is required", required),
        },
        description: {
            minLength: helpers.withMessage("Description must be at least 5 characters.", minLength(5)),
            maxLength: helpers.withMessage("Description must be at not exceed 150 characters.", maxLength(150)),
        },
    };
}