<script setup>
import {router, usePage} from '@inertiajs/vue3'
import {ref, computed, onMounted} from 'vue'
import FlashMessage from '../Components/FlashMessage.vue'
import {route} from "ziggy-js";
import {isActive, navigate} from "../utils/helpers.js";
import ConfirmDialog from "@/Components/ConfirmDialog.vue";

const drawer = ref(true)
const page = usePage()

const menuItems = computed(() => page.props.menuItems || []);

const confirmRef = ref(null)

// بعد از mount، متد open رو در window ذخیره می‌کنیم تا از هرجا قابل صدا زدن باشه
onMounted(() => {
    window.$confirm = async (message, options = {}) => {
        return await confirmRef.value.open({
            msg: message,
            ttl: options.title || 'تأیید عملیات',
            color: options.color || 'red',
        })
    }
})
</script>

<template>
    <v-app>
        <ConfirmDialog ref="confirmRef"/>

        <v-navigation-drawer
            v-model="drawer"
            permanent
            location="right"
            color="primary"
            class="elevation-3"
        >
            <v-list-item
                prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg"
                title="حسین هزاره"
                nav
            >
            </v-list-item>

            <v-divider></v-divider>

            <v-list density="comfortable" nav class="px-0">
                <template v-for="(item, i) in menuItems" :key="i">
                    <!-- Menu items with children (submenu) -->
                    <v-list-group v-if="item.children" :value="item.title" color="warning">
                        <template v-slot:activator="{ props }">
                            <v-list-item
                                v-bind="props"
                                :prepend-icon="item.icon"
                                class="pl-۱ mb-1"
                                :title="item.title"
                                :value="item.route"
                                :active="isActive(item.route) || item.children.some(child => isActive(child.route))"
                                rounded="lg"
                            ></v-list-item>
                        </template>

                        <v-list-item
                            v-for="(child, childIndex) in item.children"
                            :key="`child-${childIndex}`"
                            :title="child.title"
                            :value="child.route"
                            :prepend-icon="child.icon"
                            class="pl-2"
                            :active="isActive(child.route)"
                            @click="navigate(child.route)"
                            rounded="lg"
                        ></v-list-item>
                    </v-list-group>

                    <!-- Regular menu items -->
                    <v-list-item
                        v-else
                        :prepend-icon="item.icon"
                        :title="item.title"
                        :active="isActive(item.route)"
                        :value="item.route"
                        color="warning"
                        class="mb-1"
                        rounded="lg"
                        @click="navigate(item.route)"
                    ></v-list-item>
                </template>
            </v-list>

            <template v-slot:append>
                <div class="pa-2">
                    <v-btn
                        block
                        color="error"
                        variant="tonal"
                        prepend-icon="mdi-logout"
                        @click="router.post(route('admin.logout'))"
                    >
                        خروج از سیستم
                    </v-btn>
                </div>
            </template>
        </v-navigation-drawer>

        <v-app-bar color="white" elevation="1" density="comfortable" class="d-flex justify-space-between">


            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>



            <v-toolbar-title class="text-h6 font-weight-bold">
                پنل مدیریت
            </v-toolbar-title>

            <div class="d-flex align-center">
                <v-btn icon class="ml-2">
                    <v-badge color="error" content="2" dot>
                        <v-icon>mdi-bell</v-icon>
                    </v-badge>
                </v-btn>
            </div>

        </v-app-bar>

        <v-main class="bg-grey-lighten-3">
            <v-container fluid class="py-6 px-6">
                <v-sheet
                    min-height="calc(100vh - 140px)"
                    rounded="lg"
                    class="pa-6 bg-white"
                >
                    <slot/>
                </v-sheet>
            </v-container>
        </v-main>

        <v-footer app color="white" class="justify-center py-4">
            <div class="text-caption text-medium-emphasis">
                تمامی حقوق محفوظ است © {{ new Date().getFullYear() }} - گروه فناوری اطلاعات نکست پیکسل
            </div>
        </v-footer>

        <!-- Global Flash Message -->
        <FlashMessage/>
    </v-app>
</template>

<style scoped>
.v-navigation-drawer {
    direction: rtl;
}

.v-list-item {
    text-align: right;
}

.v-list-item__prepend {
    margin-right: 0;
    margin-left: 12px;
}

.v-list-item--active {
    background: rgba(255, 255, 255, 0.1);
}
</style>
