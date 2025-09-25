import { helpers, required, email, sameAs, minLength } from "@vuelidate/validators";

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

export function createUserValidation(form) {
    return {
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
        password: {
            required: helpers.withMessage("Password is required", required),
            minLength: helpers.withMessage("Password must be at least 8 characters.", minLength(8)),
            hasLetter,
            hasMixedCase,
            hasNumber,
            hasSymbol,
        },
        confirm_password: {
            required: helpers.withMessage("Confirm Password is required", required),
            sameAsPassword: helpers.withMessage("Confirm Password doesn't match with Password", sameAs(form.password))
        },
    }
}