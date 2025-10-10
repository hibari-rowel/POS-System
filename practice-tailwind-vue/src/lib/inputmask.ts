import Inputmask from "inputmask";

export default {
    mounted(el, binding) {
        Inputmask(binding.value).mask(el);
    },
};