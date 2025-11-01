<template>
    <v-text-field
        :model-value="formattedValue"
        @update:model-value="handleInput"
        @keydown="preventNonNumeric"
    >
        <template #append-inner v-if="rightHint">
            <small class="text-grey-darken-1">{{ rightHint }}</small>
        </template>
    </v-text-field>
</template>

<script>
export default {
    name: "FieldNumber",
    props: {
        modelValue: {
            type: [String, Number, null],
            default: "",
        },
        rightHint: {
            type: String,
            default: "",
        },
        leftHint: {
            type: String,
            default: "",
        },
        icon: {
            type: String,
            default: "",
        },
    },
    computed: {
        formattedValue() {
            if (this.modelValue === null || this.modelValue === '') return '';
            let value = String(this.modelValue).replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
    },
    methods: {
        preventNonNumeric(event) {
            // Allow: backspace, delete, tab, escape, enter, and navigation keys
            if ([8, 9, 27, 13, 35, 36, 37, 38, 39, 40, 46].includes(event.keyCode) ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+X, Ctrl+V
                (event.ctrlKey === true && [65, 67, 88, 86].includes(event.keyCode))) {
                return;
            }

            // Allow only numbers (0-9), numpad numbers, or Persian numbers
            if ((event.keyCode < 48 || event.keyCode > 57) &&
                (event.keyCode < 96 || event.keyCode > 105) &&
                !(event.keyCode >= 1776 && event.keyCode <= 1785)) {
                event.preventDefault();
            }
        },
        handleInput(value) {
            let cleanValue = value.replace(/,/g, "");
            cleanValue = cleanValue.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
            this.$emit("update:modelValue", cleanValue ? Number(cleanValue) : null);
        },
    },
};
</script>
