<template>
    <AdminLayout>
        <Head title="مدیریت دسته‌بندی‌ها"/>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-account-circle"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">لیست دسته‌بندی‌ها</strong>
                            <span>در این بخش می توانید لیست دسته‌بندی‌ دوره‌های آموزشی را مشاهده کنید.</span>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12 text-left">
                    <v-btn class="zo-add" color="primary" @click="openNewCategoryDialog">
                        <v-icon start>mdi-plus</v-icon>
                        افزودن دسته جدید
                    </v-btn>
                </v-col>
            </v-row>
        </div>
        <v-card class="pa-3 mb-3 elevation-2">
            <v-data-table
                :headers="headers"
                :items="categories.data"
                :items-per-page="categories.per_page"
                :page="categories.current_page"
                :server-items-length="categories.total"
                @update:page="changePage"
                hide-default-footer
            >
                <template v-slot:item.index="{ index }">
                    {{ index + 1 }}
                </template>
                <template v-slot:item.description="{item}">
                    {{ truncateText(item.description, 60) }}
                </template>
                <template v-slot:item.actions="{ item }">
                    <div class="d-flex justify-center ga-1">
                        <v-tooltip location="top">
                            <template v-slot:activator="{ props }">
                                <v-btn
                                    v-bind="props"
                                    icon
                                    size="small"
                                    color="primary"
                                    @click="editItem(item)"
                                >
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                            </template>
                            <span>ویرایش</span>
                        </v-tooltip>
                        <v-tooltip location="top">
                            <template v-slot:activator="{ props }">
                                <v-btn
                                    v-bind="props"
                                    icon
                                    size="small"
                                    color="secondary"
                                    @click="deleteCategory(item)"
                                    :loading="loadingItems[item.id]"
                                    :disabled="loadingItems[item.id]"
                                >
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </template>
                            <span>حذف</span>
                        </v-tooltip>
                    </div>
                </template>
            </v-data-table>
            <v-pagination
                v-if="props.categories.meta.last_page > 1"
                v-model="currentPage"
                :length="props.categories.meta.last_page"
                @update:model-value="changePage"
                class="mt-4"
            />
        </v-card>
        <CategoryDialog
            v-model="showDialog"
            :category="selectedCategory"
            :is-editing="isEditing"
            :loading="isSaving"
            @save="handleSave"
        />
    </AdminLayout>
</template>

<script setup>
import {ref, computed} from 'vue'
import {Head, router} from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CategoryDialog from '@/Components/CategoryDialog.vue'
import {route} from "ziggy-js";
import {truncateText} from "../../../../utils/helpers.js";

// Props
const props = defineProps({
    categories: {
        type: Object,
        required: true
    }
})

// State
const showDialog = ref(false)
const selectedCategory = ref(null)
const isEditing = ref(false)
const currentPage = ref(props.categories.meta.current_page)
const isSaving = ref(false)
const loadingItems = ref({})
// Computed
const headers = [
    {title: 'ردیف', key: 'index', sortable: false, align: 'center'},
    {title: 'عنوان', key: 'title', sortable: false},
    {title: 'نامک', key: 'slug', sortable: false},
    {title: 'توضیحات', key: 'description', sortable: false},
    {title: 'عملیات', key: 'actions', sortable: false, align: 'center'}
]

// Methods
const openNewCategoryDialog = () => {
    selectedCategory.value = {
        title: '',
        slug: '',
        description: '',
    }
    isEditing.value = false
    showDialog.value = true
}

const editItem = (item) => {
    selectedCategory.value = {
        id: item.id,
        title: item.title,
        slug: item.slug,
        description: item.description,
    }
    isEditing.value = true
    showDialog.value = true
}

const handleSave = async (category) => {
    const url = isEditing.value
        ? route('admin.courses.categories.update', {category: category.id})
        : route('admin.courses.categories.store')

    const method = isEditing.value ? 'put' : 'post'

    try {
        router[method](url, category, {
            preserveScroll: true,
            onStart: () => {
                isSaving.value = true
            },
            onSuccess: () => {
                showDialog.value = false
                if (!isEditing.value) {
                    router.visit(route('admin.courses.categories.index', {page: 1}), {
                        preserveScroll: true,
                        preserveState: true,
                        replace: true,
                        only: ['categories'],
                        onSuccess: () => {
                            // This ensures the pagination component updates
                            currentPage.value = 1
                        },
                    })
                }
            },
            onFinish: () => {
                isSaving.value = false
            },
        })
    } catch (error) {
        console.error('Error saving category:', error)
    }
}

const deleteCategory = async (item) => {

    const confirm = await $confirm('آیا از حذف این دسته اطمینان دارید؟');
    if (confirm) {
        try {
            router.delete(route('admin.courses.categories.destroy', item.id), {
                preserveScroll: true,
                onStart: () => {
                    loadingItems.value = {...loadingItems.value, [item.id]: true}
                },
                onSuccess: () => {
                },
                onFinish: () => {
                    const newLoading = {...loadingItems.value}
                    delete newLoading[item.id]
                    loadingItems.value = newLoading
                }
            })
        } catch (error) {
            console.error('Error deleting category:', error)
        }
    }


}

const changePage = (page) => {
    router.visit(route('admin.courses.categories.index', {page}), {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}
</script>
