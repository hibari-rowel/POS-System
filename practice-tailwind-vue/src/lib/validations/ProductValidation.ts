import { helpers, required, minLength, maxLength } from "@vuelidate/validators";
import _ from 'lodash';

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
    }
}