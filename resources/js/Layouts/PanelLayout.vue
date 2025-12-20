<template>
    <div class="zo-dashboard-section">
        <div class="zo-nav">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <nav>
                            <ul>
                                <li v-for="(item, index) in menuItems" :key="index">
                                    <a
                                        :href="item.url"
                                        :class="{'zo-active': isActive(item.url)}"
                                    >
                                        <i :class="`mdi ${item.icon}`"></i>
                                        <span>{{ item.title }}</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </v-col>
                </v-row>
            </v-container>
        </div>
        <slot />
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { route } from "ziggy-js";

const page = usePage();
const menuItems = ref(page.props.menuItems);

const isActive = (menuUrl) => {
    if (!menuUrl) return false;

    const currentUrl = window.location.pathname;
    const menuPath = new URL(menuUrl, window.location.origin).pathname;

    return currentUrl === menuPath ||
        currentUrl.startsWith(menuPath) && menuPath !== '/';
};
</script>