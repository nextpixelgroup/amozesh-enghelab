<template>
    <v-app>
        <!-- Confirm Dialog -->
        <ConfirmDialog ref="confirmRef" />

        <!-- Navigation Drawer -->
        <v-navigation-drawer
            v-model="drawer"
            permanent
            location="right"
            color="primary"
            class="elevation-2 zo-drawer-section"
        >
            <!-- Logo -->
            <div class="zo-logo py-3 d-flex justify-center">
                <img src="/assets/img/logo-typo.svg" alt="Logo">
            </div>

            <v-divider class="mx-3 my-1"></v-divider>

            <!-- Menu Items -->
            <v-list density="comfortable" class="px-1">
                <template v-for="(item, i) in menuItems" :key="i">

                    <!-- Group Menu -->
                    <v-list-group
                        v-if="item.children"
                        v-model="groupStates[item.title]"
                        @update:value="val => toggleGroup(item.title, val)"
                        no-action
                    >
                        <template #activator="{ props }">
                            <v-list-item
                                v-bind="props"
                                :prepend-icon="item.icon"
                                :title="item.title"
                                :active="isActive(item.route) || item.children?.some(child => isActive(child.route))"
                                rounded
                                class="text-white"
                            />
                        </template>

                        <!-- Sub Items -->
                        <Link
                            v-for="(child, ci) in item.children"
                            :key="`child-${ci}`"
                            :href="route(child.route)"
                            class="text-decoration-none"
                        >
                            <v-list-item
                                :prepend-icon="child.icon"
                                :title="child.title"
                                :active="isActive(child.route)"
                                rounded
                                class="text-white"
                            />
                        </Link>
                    </v-list-group>

                    <!-- Normal Items -->
                    <Link
                        v-else
                        :href="route(item.route)"
                        class="text-decoration-none"
                    >
                        <v-list-item
                            :prepend-icon="item.icon"
                            :title="item.title"
                            :active="isActive(item.route)"
                            rounded
                            class="text-white"
                        />
                    </Link>
                </template>
            </v-list>

            <!-- Social Buttons -->
            <template #append>
                <div class="d-flex justify-center py-3">
                    <ul class="zo-social d-flex gap-2">
                        <li v-for="(btn, i) in socialButtons" :key="i">
                            <v-btn icon size="small" variant="text">
                                <img :src="btn.src" :alt="btn.alt" width="18" height="18">
                            </v-btn>
                        </li>
                    </ul>
                </div>
            </template>
        </v-navigation-drawer>

        <!-- App Bar -->
        <v-app-bar
            color="white"
            elevation="2"
            density="comfortable"
            class="px-3 d-flex justify-space-between"
        >
            <v-app-bar-nav-icon @click="drawer = !drawer" />
            <v-toolbar-title class="text-h6 font-weight-bold"></v-toolbar-title>

            <div class="d-flex align-center">
                <Link :href="route('admin.tickets.index')">
                    <v-btn icon color="black">
                        <v-badge color="secondary" :content="ticketCount">
                            <v-icon>mdi-message-processing</v-icon>
                        </v-badge>
                    </v-btn>
                </Link>

                <Link :href="route('admin.profile')">
                    <v-btn icon color="black">
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
                <slot />
            </v-container>
        </v-main>

        <FlashMessage />
    </v-app>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import FlashMessage from '@/Components/FlashMessage.vue'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'
import { isActive } from '@/utils/helpers.js'

const drawer = ref(true)
const menu = ref(true)
const page = usePage()
const confirmRef = ref(null)
const isLoading = ref(false)

/* state مستقل برای هر گروه */
const groupStates = ref({})

/* From server props */
const menuItems = computed(() => page.props.menuItems || [])
const ticketCount = computed(() => page.props.ticketCount || 0)

/* Social Buttons */
const socialButtons = [
    { src: '/assets/img/soroosh-green.svg', alt: 'Soroosh' },
    { src: '/assets/img/bale-green.svg', alt: 'Bale' },
    { src: '/assets/img/eitaa-green.svg', alt: 'Eitaa' },
    { src: '/assets/img/aparat-green.svg', alt: 'Aparat' },
]

/* Global Confirm */
onMounted(() => {
    window.$confirm = async (message, options = {}) =>
        await confirmRef.value.open({
            msg: message,
            ttl: options.title || 'تأیید عملیات',
            color: options.color || 'red',
            socialButtons,
        })
})

/* باز نگه‌داشتن گروه والد وقتی زیرمنو فعال است */
watch(
    () => page.url,
    () => {
        menuItems.value.forEach(item => {
            const parentActive = isActive(item.route)
            const childActive = item.children?.some(c => isActive(c.route))

            if (parentActive || childActive) {
                groupStates.value[item.title] = true
            }
        })
    },
    { immediate: true }
)

/* toggle دستی */
const toggleGroup = (title, val) => {
    groupStates.value[title] = val
}

/* Logout */
const logout = () => {
    isLoading.value = true
    router.post(route('admin.logout'), {}, {
        onFinish: () => (isLoading.value = false)
    })
}
</script>

<style scoped>
.zo-main {
    background: rgba(245, 245, 245, 0.75);
}

a {
    display: block;
    color: #fff;
    border-radius: 0.4rem;
    transition: background 0.25s ease-in-out;
}
a:hover {
    background: rgba(255, 255, 255, 0.15);
}

.v-list-item--active {
    background: rgba(255, 255, 255, 0.25) !important;
}

.zo-social {
    list-style: none;
    margin: 0;
    padding: 0;
}
</style>
