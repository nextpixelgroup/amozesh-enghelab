<template>
    <div class="zo-dashboard-section">
        <div class="zo-nav">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <nav>
                            <ul>
                                <li v-for="(item, index) in menuItems" :key="index">
                                    <Link
                                        :href="item.url"
                                        :class="{'zo-active': isActive(item.slug)}"
                                    >
                                        <i :class="`mdi ${item.icon}`"></i>
                                        <span>{{ item.title }}</span>
                                    </Link>
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
import {Link, usePage} from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { route } from "ziggy-js";

const page = usePage();
const menuItems = ref(page.props.menuItems);

const isActive = (slug) => {
    if (!slug) return false;

    const currentPath = window.location.pathname; // e.g., /panel/bookmarks/courses

    if (Array.isArray(slug)) {
        return slug.some(s => {
            const slugPath = `panel/${s}`.replace(/\/+$/, '');
            return currentPath === `/${slugPath}` ||
                currentPath.startsWith(`/${slugPath}/`);
        });
    }

    const slugPath = `panel/${slug}`.replace(/\/+$/, '');
    return currentPath === `/${slugPath}` ||
        currentPath.startsWith(`/${slugPath}/`);
};
</script>
