<script>
import {Form, Head, usePage} from '@inertiajs/vue3'
import {ref} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";
import {formHandler} from "../../../utils/helpers.js";

export default {
    methods: {route},
    components: {AdminLayout, Head},
    setup() {
        const page = usePage();
        const { form, submitForm, isLoading } = formHandler({
            name: ref(''),
            email: ref(''),
        });

        const addUser = () => {
            submitForm('admin.users.store');
        };

        return { form, submitForm, isLoading, addUser };
    }

}

</script>

<template>
    <AdminLayout>
        <Head title="Create User" />
        <h1>Users</h1>
        <v-form @submit.prevent>
            <v-text-field type="text" v-model="form.name" />
            <v-text-field type="email" v-model="form.email" />
            <v-btn type="submit" @click="addUser">Create User</v-btn>
        </v-form>
    </AdminLayout>
</template>
