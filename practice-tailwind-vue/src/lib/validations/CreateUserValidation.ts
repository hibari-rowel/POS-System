import { helpers, required, email, sameAs, minLength } from "@vuelidate/validators";
import _ from 'lodash';

const hasLetter = helpers.withMessage(
    'Password must contain at least one letter',
    value => /[a-zA-Z]/.test(value)
)

const hasMixedCase = helpers.withMessage(
    'Password must contain both uppercase and lowercase letters',
    value => /[a-z]/.test(value) && /[A-Z]/.test(value)
)

const hasNumber = helpers.withMessage(
    'Password must contain at least one number',
    value => /\d/.test(value)
)

const hasSymbol = helpers.withMessage(
    'Password must contain at least one symbol',
    value => /[^A-Za-z0-9]/.test(value)
)

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

export function createUserValidation(form) {
    let rules = {
        first_name: {
            required: helpers.withMessage("First Name is required", required),
        },
        middle_name: {
            required: helpers.withMessage("Middle Name is required", required),
        },
        last_name: {
            required: helpers.withMessage("Last Name is required", required),
        },
        role: {
            required: helpers.withMessage("Role is required", required),
        },
        status: {
            required: helpers.withMessage("Active Status is required", required),
        },
        email: {
            required: helpers.withMessage("Email is required", required),
            email: helpers.withMessage("Please enter a valid Email", email)
        },
        image: {
            maxSize: maxSize(10048), // 10048KB = 10MB
            allowedTypes: allowedTypes(['image/jpg', 'image/jpeg', 'image/png', 'image/gif']),
        }
    };

    if (_.has(form, 'password')) {
        rules.password = {
            required: helpers.withMessage("Password is required", required),
            minLength: helpers.withMessage("Password must be at least 8 characters.", minLength(8)),
            hasLetter,
            hasMixedCase,
            hasNumber,
            hasSymbol,
        };
    }

    if (_.has(form, 'confirm_password')) {
        rules.confirm_password = {
            required: helpers.withMessage("Confirm Password is required", required),
            sameAsPassword: helpers.withMessage("Confirm Password doesn't match with Password", sameAs(form.password))
        };
    }

    return rules;
}