<template>
    <Head :title="adminPageTitle" />
    <SettingLayout>
        <v-card v-for="(menu, menuIndex) in menus" :key="menu.type" class="mb-6">
            <v-toolbar color="green-lighten-5" density="compact" class="px-2 border-b">
                <v-icon color="green-darken-2" start>mdi-menu</v-icon>
                <v-toolbar-title class="text-body-1 font-weight-bold green">
                    {{ menu.title }}
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn
                    v-if="menu.items.length > 0"
                    color="primary"
                    size="small"
                    variant="tonal"
                    @click="addNewItem(menu)"
                    prepend-icon="mdi-plus"
                    :disabled="isLoading"
                >
                    افزودن آیتم
                </v-btn>
            </v-toolbar>

            <v-card-text>
                <div
                    v-if="menu.items.length > 0"
                    ref="menuItemsContainer"
                    class="menu-items"
                >
                    <div
                        v-for="(item, itemIndex) in menu.items"
                        :key="`item-${itemIndex}`"
                        class="menu-item d-flex align-center pa-2 mb-2"
                    >
                        <v-icon class="drag-handle me-2" icon="mdi-drag"></v-icon>

                        <div class="flex-grow-1">
                            <div class="d-flex align-center">
                                <!--<v-icon v-if="item.icon" :icon="item.icon" class="me-2"></v-icon>-->
                                <div>
                                    <div class="text-subtitle-1">{{ item.title }}</div>
                                    <div class="text-caption text-medium-emphasis">{{ item.url }}</div>
                                </div>
                            </div>
                        </div>

                        <v-btn
                            icon
                            size="small"
                            color="primary"
                            variant="flat"
                            @click="editItem(menu, item, itemIndex)"
                            :disabled="isLoading"
                            class="edit-btn mx-1"
                        >
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn
                            icon
                            size="small"
                            color="error"
                            variant="flat"
                            :loading="deletingItemId === item.id"
                            :disabled="isLoading"
                            @click="deleteItem(menu, itemIndex)"
                            class="delete-btn ml-2"
                        >
                            <v-icon size="small">mdi-close</v-icon>
                        </v-btn>
                    </div>
                </div>

                <v-sheet
                    v-else
                    class="d-flex flex-column align-center justify-center pa-8 text-center rounded-lg"
                    color="grey-lighten-4"
                    min-height="180"
                    elevation="0"
                    rounded="lg"
                >
                    <v-icon
                        size="48"
                        color="grey-darken-1"
                        class="mb-4"
                    >
                        mdi-menu
                    </v-icon>
                    <div class="text-h6 font-weight-medium text-grey-darken-1 mb-2">آیتمی وجود ندارد</div>
                    <p class="text-body-2 text-grey-darken-1 mb-4">شما هنوز هیچ آیتمی به این فهرست اضافه نکرده‌اید</p>
                    <v-btn
                        color="primary"
                        variant="tonal"
                        size="small"
                        prepend-icon="mdi-plus"
                        @click="addNewItem(menu)"
                        :disabled="isLoading"
                    >
                        افزودن آیتم جدید
                    </v-btn>
                </v-sheet>
            </v-card-text>
            <v-card class="px-4 pb-4" v-if="typeSortable === menu.type">
                <v-btn
                    color="primary"
                    size="small"
                    @click="saveSortable(menu.type)"
                    prepend-icon="mdi-pencil"
                    class="me-auto"
                    :loading="sortingMenuType === menu.type"
                    :disabled="isLoading"
                >
                   ذخیره جابجایی
                </v-btn>
            </v-card>
        </v-card>

        <!-- Add/Edit Dialog -->
        <v-dialog
            v-model="dialog"
            max-width="500"
            @update:model-value="onDialogClosed"

        >
            <v-card>
                <v-card-title>
                    {{ formTitle }}
                </v-card-title>

                <v-card-text>
                    <v-text-field
                        v-model="editedItem.title"
                        variant="outlined"
                        density="comfortable"
                        label="عنوان"
                        required
                        class="mb-3"
                    ></v-text-field>

                    <v-text-field
                        v-model="editedItem.url"
                        variant="outlined"
                        density="comfortable"
                        label="لینک"
                        required
                        class="mb-3"
                        dir="ltr"
                    ></v-text-field>

                    <v-select
                        v-model="editedItem.target"
                        variant="outlined"
                        density="comfortable"
                        :items="targets"
                        label="نحوه باز شدن لینک"
                        item-title="text"
                        item-value="value"
                        class="mb-3"
                    ></v-select>

<!--                    <v-text-field
                        v-model="editedItem.icon"
                        label="آیکون (اختیاری)"
                        placeholder="mdi-icon-name"
                        class="mb-3"
                        :prepend-icon="editedItem.icon || 'mdi-help-circle-outline'"
                    ></v-text-field>-->
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" variant="text" @click="closeDialog">انصراف</v-btn>
                    <v-btn color="primary" @click="saveItem" :loading="isLoading" :disabled="isLoading">ذخیره</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </SettingLayout>
</template>

<script setup>
import {ref, onMounted, nextTick, computed, watch} from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { useSortable } from '@vueuse/integrations/useSortable'
import SettingLayout from '@/Components/Admin/SettingLayout.vue'
import { usePage } from '@inertiajs/vue3'
import usePageTitle from "@/Composables/usePageTitle.js";
import {route} from "ziggy-js";
const {adminPageTitle} = usePageTitle('فهرست‌ها')
const props = defineProps({
    menus: {
        type: Array,
        required: true
    }
})

const menuItemsContainer = ref([])
const formTitle = ref('')
const dialog = ref(false)
const editedIndex = ref(-1)
const menus = computed(() => props.menus)
const typeSortable = ref(false)

const isLoading = ref(false)
const deletingItemId = ref(null);
const sortingMenuType = ref(null);
const errorMessage = ref('')

const targets = [
    { text: 'همان صفحه', value: '_self' },
    { text: 'تب جدید', value: '_blank' }
]

const editedItem = ref({
    id: null,
    title: '',
    url: '',
    target: '_self',
    icon: '',
    type: '',
    order: 0
})

// Initialize sortable for each menu
onMounted(() => {
    nextTick(() => {
        menuItemsContainer.value.forEach((container, index) => {
            if (container) {
                useSortable(container, menus.value[index].items, {
                    animation: 150,
                    handle: '.drag-handle',
                    onEnd: () => {
                        console.log(menus.value[index].type)
                        typeSortable.value = menus.value[index].type
                    },
                })
            }
        })
    })
})


async function saveSortable(type) {
    try {
        const findMenu = props.menus.find(menu => menu.type === type);
        if (!findMenu) return;

        // Create a deep copy of the menu items to avoid mutating the props directly
        const menuData = JSON.parse(JSON.stringify({
            ...findMenu,
            items: [...findMenu.items]
        }));

        router.put(
            route('admin.settings.menus.order'),
            {
                data: [menuData],
            },
            {
                preserveState: false, // Let Inertia update the page props
                preserveScroll: true,
                onStart: () => {
                    isLoading.value = true
                    sortingMenuType.value = type;
                },
                onSuccess: () => {
                    isLoading.value = false;
                    sortingMenuType.value = null;
                    typeSortable.value = false;
                    // The page will be refreshed by Inertia due to preserveState: false
                },
                onError: (errors) => {
                    isLoading.value = false;
                    sortingMenuType.value = null;
                    console.error('Error saving menu order:', errors);
                }
            },
        );
    } catch (error) {
        console.error('Error in saveSortable:', error);
    }
}

function addNewItem(menu) {
    formTitle.value = 'افزودن آیتم جدید'
    editedItem.value = {
        id: null,
        title: '',
        url: '',
        target: '_self',
        icon: '',
        type: menu.type,
        order: menu.items.length + 1
    }
    dialog.value = true
}

function editItem(menu, item, index) {
    editedIndex.value = index
    formTitle.value = 'ویرایش آیتم'
    editedItem.value = { ...item }
    dialog.value = true
}

async function deleteItem(menu, index) {
    const confirm = await $confirm('آیا از حذف این آیتم اطمینان دارید؟')
    if (!confirm) {
        return;
    }

    try {

        errorMessage.value = ''

        // Make a copy of the item before removing it
        const itemToDelete = { ...menu.items[index] }

        // Optimistic UI update
        //menu.items.splice(index, 1)

        // If it's a new item that hasn't been saved to the server yet
        if (typeof itemToDelete.id === 'number' && itemToDelete.id < 0) {
            return // No need to send to server
        }

        // Send delete request to server
        await router.delete(route('admin.settings.menus.destroy', { menu: itemToDelete.id }), {
            preserveScroll: true,
            preserveState: true,
            onStart: () => {
                isLoading.value = true
                const itemId = menu.items[index].id;
                deletingItemId.value = itemId;
            },
            onSuccess: () => {
                deletingItemId.value = null;
                isLoading.value = false
            },
            onError: (errors) => {
                deletingItemId.value = null;
                isLoading.value = false
                // Revert the UI if the request fails
                //menu.items.splice(index, 0, itemToDelete)
                errorMessage.value = 'خطا در حذف آیتم. لطفاً دوباره تلاش کنید.'
                console.error('Error deleting menu item:', errors)
            }
        })
    } catch (error) {
        console.error('Error deleting menu item:', error)
        errorMessage.value = 'خطا در حذف آیتم. لطفاً دوباره تلاش کنید.'
    }
}

function onDialogClosed(isOpen) {
    if (!isOpen) {
        closeDialog();
    }
}

function closeDialog() {
    dialog.value = false;
    editedItem.value = {
        id: null,
        title: '',
        url: '',
        target: '_self',
        icon: '',
        type: '',
        order: 0
    };
    editedIndex.value = -1;
    errorMessage.value = '';
}

async function saveItem() {
    try {
        errorMessage.value = ''

        // Prepare the menu item data
        const menuData = { ...editedItem.value }

        // Remove temporary ID if it's a new item
        if (menuData.id && menuData.id < 0) {
            delete menuData.id
        }

        // Determine if we're creating or updating
        const isNew = editedIndex.value === -1

        if (isNew) {
            // Add the new item to the UI immediately (optimistic update)
            const newItem = {
                ...menuData,
                id: -Date.now(), // Temporary negative ID for new items
            }
            // Send create request to server
            await router.post(route('admin.settings.menus.store'), {
                ...newItem,
            }, {
                preserveState: false, // Let Inertia update the page props
                preserveScroll: true,
                onStart: () => {
                    isLoading.value = true
                },
                onSuccess: () => {
                    isLoading.value = false;
                    closeDialog();
                },
                onError: (errors) => {
                    isLoading.value = false;
                    closeDialog();
                    throw errors;
                }
            })
        } else {
            try {
                // Send update request to server
                await router.put(route('admin.settings.menus.update', {
                    menu: menuData.id
                }), menuData, {
                    preserveState: false, // Let Inertia update the page props
                    preserveScroll: true,
                    onStart: () => {
                        isLoading.value = true
                    },
                    onSuccess: () => {
                        isLoading.value = false;
                        closeDialog();
                    },
                    onError: (errors) => {
                        isLoading.value = false;
                        closeDialog();
                        throw errors;
                    }
                })
            } catch (error) {
                console.error('Error updating menu item:', error)
                throw error
            }
        }

        // No need to manually close dialog as page will refresh due to preserveState: false
    } catch (error) {
        console.error('Error saving menu item:', error);
        errorMessage.value = 'خطا در ذخیره‌سازی. لطفاً دوباره تلاش کنید.';
    }
}

// Get the page object for Inertia
const page = usePage()
</script>

<style scoped>
.menu-items {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 8px;
}

.menu-item {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    transition: all 0.2s;
}

.menu-item:hover {
    background-color: #f5f5f5;
}

.drag-handle {
    cursor: move;
    opacity: 0.6;
    transition: opacity 0.2s;
}

.drag-handle:hover {
    opacity: 1;
}

.v-overlay {
    z-index: 2000 !important;
}

.delete-btn {
    width: 24px;
    height: 24px;
    border-radius: 4px;
}
.edit-btn {
    width: 24px;
    height: 24px;
    border-radius: 4px;
}
</style>
