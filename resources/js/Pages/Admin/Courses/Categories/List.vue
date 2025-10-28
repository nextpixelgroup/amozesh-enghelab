<script setup>
import {Head, router, usePage} from '@inertiajs/vue3'
import {ref, computed} from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CategoryDialog from '@/Components/CategoryDialog.vue'
import {route} from 'ziggy-js';

const props = defineProps({
    categories: Object, // از لاراول می‌آید (با paginate)
})
console.log(props.categories.data)

const headers = [
    {title: 'عنوان', key: 'title'},
    {title: 'توضیحات', key: 'description'},
    {title: 'وضعیت', key: 'is_active'},
    {title: 'عملیات', key: 'actions', sortable: false},
]
const items = ref(props.categories.data)
const dialog = ref(false)
const currentCategory = ref({
    id: null,
    description: '',
    is_active: true
})
const isEditing = ref(false)

const saveCategory = (category) => {
    console.log(category)
    const url = isEditing.value
        ? route('admin.courses.categories.update', {category: category.id})
        : route('admin.courses.categories.store');

    const method = isEditing.value ? 'put' : 'post';

    router[method](url, category, {
        preserveScroll: true,
        onSuccess: (response) => {

            resetForm();
        },
        onError: (errors) => {
            console.error('Validation errors:', errors);
            // You can handle form errors here
        }
    });
};

const editItem = (item) => {
    isEditing.value = true
    currentCategory.value = {
        id: item.id,
        title: item.title,
        slug: item.slug || '',
        description: item.description,
        is_active: item.is_active === 'فعال'
    }
    dialog.value = true
}

const deleteItem = async (item) => {
    const ok = await $confirm('آیا مطمئنی می‌خوای این آیتم حذف بشه؟')
    if (!ok) return

    router.delete(route('admin.courses.categories.destroy', {category: item.id}), {
        preserveScroll: true,
        onSuccess: () => {
            // Remove from local state
            const index = items.value.findIndex(i => i.id === item.id);
            if (index !== -1) {
                items.value.splice(index, 1);
            }
            console.log('دسته با موفقیت حذف شد');
            // You might want to use a toast notification here
        },
        onError: (errors) => {
            console.error('خطا در حذف دسته:', errors);
            // Handle errors here
        }
    });
};

const resetForm = () => {
    currentCategory.value = {
        id: null,
        title: '',
        is_active: true
    }
    isEditing.value = false
    dialog.value = false
}

const openNewCategoryDialog = () => {
    isEditing.value = false
    resetForm()
    dialog.value = true
}
</script>

<template>
    <AdminLayout>
        <Head title="مدیریت دسته‌بندی‌ها"/>

        <v-container fluid class="pa-6">
            <v-card class="mb-6">
                <v-toolbar color="primary" dark>
                    <v-toolbar-title>لیست دسته‌بندی‌ها</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn color="white" dark @click="openNewCategoryDialog">
                        <v-icon start>mdi-plus</v-icon>
                        افزودن دسته جدید
                    </v-btn>
                </v-toolbar>

                <v-card-text>
                    <v-data-table
                        :headers="headers"
                        :items="items"
                        :items-per-page="10"
                        class="elevation-1"
                        no-filter
                        hide-default-footer
                        disable-sort
                    >
                        <template v-slot:item.is_active="{ item }">
                            <v-chip :color="item.isActive.value ? 'success' : 'error'" dark>
                                {{ item.isActive.label }}
                            </v-chip>
                        </template>

                        <template v-slot:item.actions="{ item }">
                            <v-icon
                                small
                                class="me-2"
                                @click="editItem(item)"
                            >
                                mdi-pencil
                            </v-icon>
                            <v-icon
                                small
                                @click="deleteItem(item)"
                            >
                                mdi-delete
                            </v-icon>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-container>

        <!-- Category Dialog Component -->
        <CategoryDialog
            v-model="dialog"
            :category="currentCategory"
            :is-editing="isEditing"
            @save="saveCategory"/>
    </AdminLayout>
</template>
