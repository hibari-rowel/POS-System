import { helpers, required, minLength, maxLength } from "@vuelidate/validators";
import _ from 'lodash';

const maxSize = (sizeKb: number) =>
    helpers.withMessage(
        `File size must be less than ${sizeKb}KB`,
        (value: File | null) => !value || value.size / 1024 <= sizeKb
    );

const allowedTypes = (types: string[]) =>
    helpers.withMessage(
        `Only ${types.join(", ")} files are allowed`,
        (value: File | null) => !value || types.includes(value.type)
    );

const normalizePrice = (value) => {
    if (!value) return "";
    return String(value).replace(/[₱,\s]/g, "");
};

const decimal = helpers.withMessage(
    "Selling price must be a valid decimal number (2 to 4 decimal places).",
    (value) => {
        const cleaned = normalizePrice(value);
        return /^\d+(\.\d{2,4})?$/.test(cleaned);
    }
);


export function productValidation(form) {
    return {
        name: {
            required: helpers.withMessage("Name is required", required),
            minLength: helpers.withMessage("Name must be at least 5 characters.", minLength(5)),
        },
        description: {
            minLength: helpers.withMessage("Name must be at least 5 characters.", minLength(5)),
            maxLength: helpers.withMessage("Name must be at not exceed 150 characters.", maxLength(150)),
        },
        sku: {},
        unit: {
            required: helpers.withMessage("Unit is required", required),
        },
        product_category: {
            required: helpers.withMessage("Category is required", required),
        },
        selling_price: {
            required: helpers.withMessage("Selling Price is required", required),
            decimal,
        },
        image: {
            maxSize: maxSize(10048),
            allowedTypes: allowedTypes(['image/jpg', 'image/jpeg', 'image/png', 'image/gif']),
        }
    }
}