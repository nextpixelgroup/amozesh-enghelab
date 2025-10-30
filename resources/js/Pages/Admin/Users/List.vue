<template>
    <AdminLayout>
        <Head title="مدیریت کاربران" />
        <v-container fluid class="pa-6">
            <v-card class="mb-6">
                <v-toolbar color="primary" dark>
                    <v-toolbar-title>لیست کاربران</v-toolbar-title>
                    <v-spacer />
                    <Link :href="route('admin.users.create')">
                        <v-btn color="white">
                            <v-icon start>mdi-plus</v-icon>
                            افزودن کاربر جدید
                        </v-btn>
                    </Link>
                </v-toolbar>

                <v-card-text>
                    <v-data-table
                        :headers="headers"
                        :items="users?.data"
                        :items-per-page="users?.per_page"
                        :page="users?.current_page"
                        :server-items-length="users?.total"
                        @update:page="changePage"
                        hide-default-footer
                        class="elevation-1"
                    >
                        <template v-slot:item.status="{ item }">
                            <v-chip :color="item.status.value == 'active' ? 'success' : 'error'" dark small>
                                {{ item.status.title }}
                            </v-chip>
                        </template>

                        <template v-slot:item.actions="{ item }">
                            <div class="d-flex">
                                <v-tooltip location="top">
                                    <template v-slot:activator="{ props }">
                                        <Link :href="route('admin.users.edit', item.id)">
                                            <v-btn
                                                v-bind="props"
                                                icon
                                                size="small"
                                                color="primary"
                                                variant="text"
                                                class="mx-1"
                                            >
                                                <v-icon>mdi-pencil</v-icon>
                                            </v-btn>
                                        </Link>
                                    </template>
                                    <span>ویرایش</span>
                                </v-tooltip>
                            </div>
                        </template>
                    </v-data-table>

                    <v-pagination
                        v-if="props.users?.meta.last_page > 1"
                        v-model="currentPage"
                        :length="props.users?.meta.last_page"
                        @update:model-value="changePage"
                        class="mt-4"
                    />
                </v-card-text>
            </v-card>
        </v-container>

    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import {Head, Link, router} from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {route} from "ziggy-js";

// Props
const props = defineProps({
    users: Object
})
console.log(props.users.data)
// State
const currentPage = ref(props.users?.meta.current_page)
// Computed
const headers = [
    { title: 'شناسه', key: 'id', sortable: false, align: 'center' },
    { title: 'نام', key: 'fullname', sortable: false, align: 'center' },
    { title: 'نقش کاربری', key: 'role.title', sortable: false, align: 'center' },
    { title: 'همراه', key: 'mobile', sortable: false, align: 'center' },
    { title: 'وضعیت', key: 'status', sortable: false, align: 'center' },
    { title: 'عملیات', key: 'actions', sortable: false, align: 'center' }
]



const changePage = (page) => {
    router.visit(route('admin.users.index', { page }), {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}
</script>

<style scoped>
.v-data-table :deep(td) {
    white-space: nowrap;
}
</style>
