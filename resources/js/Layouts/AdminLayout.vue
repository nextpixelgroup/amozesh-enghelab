<template>
    <v-app>
        <ConfirmDialog ref="confirmRef"/>

        <v-navigation-drawer
            v-model="drawer"
            permanent
            location="right"
            color="primary"
            class="elevation-2 zo-drawer-section"
        >
            <div class="zo-logo">
                <img src="/assets/img/logo-typo.svg" alt="">
            </div>

            <div class="px-3">
                <v-divider></v-divider>
            </div>

            <v-list density="comfortable" class="px-1">
                <template v-for="(item, i) in menuItems" :key="i">
                    <!-- Menu items with children (submenu) -->
                    <v-list-group v-if="item.children" :value="item.title">
                        <template v-slot:activator="{ props }">
                            <v-list-item
                                v-bind="props"
                                :prepend-icon="item.icon"
                                :title="item.title"
                                :value="item.route"
                                :active="isActive(item.route) || item.children.some(child => isActive(child.route))"
                                rounded
                            ></v-list-item>
                        </template>

                        <v-list-item
                            v-for="(child, childIndex) in item.children"
                            :key="`child-${childIndex}`"
                            :title="child.title"
                            :value="child.route"
                            :prepend-icon="child.icon"
                            :active="isActive(child.route)"
                            @click="navigate(child.route)"
                            rounded
                        ></v-list-item>
                    </v-list-group>

                    <!-- Regular menu items -->
                    <v-list-item
                        v-else
                        :prepend-icon="item.icon"
                        :title="item.title"
                        :active="isActive(item.route)"
                        :value="item.route"
                        @click="navigate(item.route)"
                        rounded
                    ></v-list-item>
                </template>
            </v-list>

            <template v-slot:append>
                <ul class="zo-social">
                    <li v-for="(btn, index) in socialButtons" :key="index">
                        <v-btn>
                            <img :src="btn.src" :alt="btn.alt" width="15" height="15"/>
                        </v-btn>
                    </li>
                </ul>
            </template>
        </v-navigation-drawer>

        <v-app-bar color="white" elevation="2" density="comfortable" class="d-flex justify-space-between px-3 mb-3">
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title class="text-h6 font-weight-bold">
            </v-toolbar-title>

            <div class="d-flex align-center">
                <v-btn icon>
                    <v-badge color="secondary" :content="ticketCount">
                        <v-icon>mdi-message-processing</v-icon>
                    </v-badge>
                </v-btn>
                <Link :href="route('admin.profile')">
                    <v-btn icon>
                        <v-icon>mdi-account-circle</v-icon>
                    </v-btn>
                </Link>
                <v-btn icon @click="logout" :loading="isLoading">
                    <v-icon>mdi-logout</v-icon>
                </v-btn>
            </div>

        </v-app-bar>

        <v-main class="zo-main">
            <v-container fluid>
                <slot/>
            </v-container>
        </v-main>

        <!-- Global Flash Message -->
        <div class="text-center text-caption text-grey py-2">
            طراحی و پیاده‌سازی توسط <a href="https://nextpixel.ir" target="_blank">نکست پیکسل</a>
        </div>
        <FlashMessage/>
    </v-app>
</template>
<script setup>
import '@/../css/admin.css';
import {Link, router, usePage} from '@inertiajs/vue3'
import {ref, computed, onMounted} from 'vue'
import FlashMessage from '../Components/FlashMessage.vue'
import {route} from "ziggy-js";
import {isActive, navigate} from "../utils/helpers.js";
import ConfirmDialog from "@/Components/ConfirmDialog.vue";

const drawer = ref(true)
const page = usePage()

const menuItems = computed(() => page.props.menuItems || []);
const ticketCount = computed(() => page.props.ticketCount || 0);

const confirmRef = ref(null)
const isLoading = ref(null)

const socialButtons = [
    {src: '/assets/img/soroosh-green.svg', alt: 'Soroosh'},
    {src: '/assets/img/bale-green.svg', alt: 'Bale'},
    {src: '/assets/img/eitaa-green.svg', alt: 'Eitaa'},
    {src: '/assets/img/aparat-green.svg', alt: 'Aparat'},
]

onMounted(() => {
    window.$confirm = async (message, options = {}) => {
        return await confirmRef.value.open({
            msg: message,
            ttl: options.title || 'تأیید عملیات',
            color: options.color || 'red',
            socialButtons
        })
    }
})

const logout = () => {
    isLoading.value = true
    router.post(route('admin.logout'), {
        onFinish: () => {
            isLoading.value = false;
        }
    })
}

</script>
<style>
.zo-main {
    background: rgb(245, 245, 245,.75)
}
</style>
