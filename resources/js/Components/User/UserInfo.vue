<template>
    <v-row>

    </v-row>
</template>

<script setup>
const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    roles: {
        type: Array,
        default: () => []
    }
})
</script>
