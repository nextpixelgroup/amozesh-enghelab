<script setup>
import {Form, Head, useForm, usePage} from '@inertiajs/vue3'
import {defineComponent, ref} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";
import {formHandler} from "../../../utils/helpers.js";

defineComponent({
    components: {AdminLayout, Head}
})
const form = useForm({
    'firstname': '',
    'lastname': '',
    'mobile': '',
    'email': '',
});

const addUser = () => {
    form.post(route('admin.users.store'));
};

</script>

<template>
    <AdminLayout>
        <Head title="Create User" />
        <h1>Users</h1>
        <v-form @submit.prevent="addUser">
            <v-text-field type="text" v-model="form.name" />
            <v-text-field type="email" v-model="form.email" />
            <v-btn type="submit" @click="addUser">ایجاد کاربر</v-btn>
        </v-form>
    </AdminLayout>
</template>
