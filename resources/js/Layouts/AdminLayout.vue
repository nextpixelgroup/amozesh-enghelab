<script setup>
import {Head, Link, router, usePage} from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import FlashMessage from '../Components/FlashMessage.vue'
import {route} from "ziggy-js";

const drawer = ref(true)
const rail = ref(false)
const page = usePage()

const menuItems = [
  { title: 'کاربران', icon: 'mdi-account-group', route: 'admin.users.index' },
  { title: 'دروس', icon: 'mdi-book-open-page-variant', route: 'admin.courses.index' },
  { title: 'کتاب‌ها', icon: 'mdi-book', route: 'admin.books.index' },
  { title: 'مسیرها', icon: 'mdi-routes', route: 'admin.paths.index' },
  { title: 'سفارشات', icon: 'mdi-cart', route: 'admin.orders.index' },
]

const isActive = (routeName) => {
  // Get the full route path and clean it
  const routePath = new URL(route(routeName), window.location.origin).pathname;
  const currentPath = window.location.pathname;

  // Normalize paths (remove trailing slashes for consistent comparison)
  const normalizedRoutePath = routePath.replace(/\/+$/, '');
  const normalizedCurrentPath = currentPath.replace(/\/+$/, '');

  // Check for exact match or if current path starts with route path
  return normalizedCurrentPath === normalizedRoutePath ||
         normalizedCurrentPath.startsWith(normalizedRoutePath + '/');
}

const navigate = (routeName) => {
  router.visit(route(routeName), { preserveState: true });
}
</script>

<template>
  <v-app>
    <v-navigation-drawer
      v-model="drawer"
      :rail="rail"
      permanent
      location="right"
      @click="rail = false"
      color="primary"
      class="elevation-3"
    >
      <v-list-item
        prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg"
        title="حسین هزاره"
        nav
      >
        <template v-slot:append>
          <v-btn
            variant="text"
            :icon="rail ? 'mdi-chevron-left' : 'mdi-chevron-right'"
            @click.stop="rail = !rail"
          ></v-btn>
        </template>
      </v-list-item>

      <v-divider></v-divider>

      <v-list density="compact" nav>
        <v-list-item
          v-for="(item, i) in menuItems"
          :key="i"
          :prepend-icon="item.icon"
          :title="item.title"
          :active="isActive(item.route)"
          :value="item.route"
          active-color="warning"
          class="mb-1"
          rounded="lg"
          @click="navigate(item.route)"
          style="cursor: pointer;"
        ></v-list-item>
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

    <v-app-bar color="white" elevation="1" density="comfortable">
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title class="text-h6 font-weight-bold">
        پنل مدیریت
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon>
        <v-badge color="error" content="2" dot>
          <v-icon>mdi-bell-outline</v-icon>
        </v-badge>
      </v-btn>
    </v-app-bar>

    <v-main class="bg-grey-lighten-3">
      <v-container fluid class="py-6 px-6">
        <v-sheet
          min-height="calc(100vh - 140px)"
          rounded="lg"
          class="pa-6 bg-white"
        >
          <slot />
        </v-sheet>
      </v-container>
    </v-main>

    <v-footer app color="white" class="justify-center py-4">
      <div class="text-caption text-medium-emphasis">
        تمامی حقوق محفوظ است © {{ new Date().getFullYear() }} - سیستم مدیریت محتوا
      </div>
    </v-footer>

    <!-- Global Flash Message -->
    <FlashMessage />
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
