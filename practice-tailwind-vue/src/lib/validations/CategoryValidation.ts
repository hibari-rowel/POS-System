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

export function categoryValidation(form) {
    return {
        name: {
            required: helpers.withMessage("Name is required", required),
            minLength: helpers.withMessage("Name must be at least 5 characters.", minLength(5)),
        },
        description: {
            minLength: helpers.withMessage("Name must be at least 5 characters.", minLength(5)),
            maxLength: helpers.withMessage("Name must be at not exceed 150 characters.", maxLength(150)),
        },
        image: {
            maxSize: maxSize(10048),
            allowedTypes: allowedTypes(['image/jpg', 'image/jpeg', 'image/png', 'image/gif']),
        }
    };
}