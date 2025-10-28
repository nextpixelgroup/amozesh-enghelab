<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CategoryDialog from '@/Components/CategoryDialog.vue'
import { route } from 'ziggy-js';
const headers = [
  { title: 'عنوان', key: 'title' },
  { title: 'توضیحات', key: 'description' },
  { title: 'وضعیت', key: 'status' },
  { title: 'عملیات', key: 'actions', sortable: false },
]

const items = ref([
  {
    id: 1,
    title: 'دسته تستی',
    description: 'این یک دسته تستی است',
    status: 'فعال',
  },
])

const dialog = ref(false)
const currentCategory = ref({
  id: null,
  description: '',
  status: true
})
const isEditing = ref(false)

const saveCategory = (category) => {
  const url = isEditing.value 
    ? route('admin.courses.categories.update', { category: category.id })
    : route('admin.courses.categories.store');
    
  const method = isEditing.value ? 'put' : 'post';
  
  router[method](url, category, {
    preserveScroll: true,
    onSuccess: (response) => {
      if (isEditing.value) {
        // Update existing category in the list
        const index = items.value.findIndex(item => item.id === category.id);
        if (index !== -1) {
          items.value[index] = { 
            ...response.props.categories.find(cat => cat.id === category.id),
            status: response.props.categories.find(cat => cat.id === category.id).status ? 'فعال' : 'غیرفعال'
          };
        }
      } else {
        // Add new category to the beginning of the list
        items.value.unshift({
          ...response.props.categories[0],
          status: response.props.categories[0].status ? 'فعال' : 'غیرفعال'
        });
      }
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
    status: item.status === 'فعال'
  }
  dialog.value = true
}

const deleteItem = (item) => {
  if (!confirm(`آیا از حذف دسته "${item.title}" اطمینان دارید؟`)) {
    return;
  }

  router.delete(route('admin.courses.categories.destroy', { category: item.id }), {
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
    status: true
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
    <Head title="مدیریت دسته‌بندی‌ها" />

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
          >
            <template v-slot:item.status="{ item }">
              <v-chip :color="item.status === 'فعال' ? 'success' : 'error'" dark>
                {{ item.status }}
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
