<template>
    <v-app id="inspire">
        <ConfirmDialog ref="confirmRef" />

        <v-navigation-drawer
            v-model="drawer"
            location="right"
            color="primary"
            class="elevation-2 zo-drawer-section"
            :temporary="!mdAndUp"
        >
            <div class="zo-logo py-3 d-flex justify-center">
                <img src="/assets/img/logo-typo.svg" alt="Logo">
            </div>

            <v-divider class="mx-3 my-1"></v-divider>

            <v-list density="comfortable" class="px-1">
                <template v-for="(item, i) in menuItems" :key="i">

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

            <template #append>
                <div class="d-flex justify-center py-3">
                    <ul class="zo-social elevation-4 d-flex gap-2">
                        <li>
                            <a :href="socialButtons.bale" target="_blank">
                                <v-btn icon size="small" variant="text">
                                    <img src="/assets/img/social/bale-white.svg" alt="bale" width="18" height="18">
                                </v-btn>
                            </a>
                        </li>
                        <li>
                            <a :href="socialButtons.eitaa" target="_blank">
                                <v-btn icon size="small" variant="text">
                                    <img src="/assets/img/social/eitaa-white.svg" alt="eitaa" width="18" height="18">
                                </v-btn>
                            </a>
                        </li>
                        <li>
                            <a :href="socialButtons.instagram" target="_blank">
                                <v-btn icon size="small" variant="text">
                                    <img src="/assets/img/social/instagram-white.svg" alt="instagram" width="18" height="18">
                                </v-btn>
                            </a>
                        </li>
                        <li>
                            <a :href="socialButtons.bale" target="_blank">
                                <v-btn icon size="small" variant="text">
                                    <img src="/assets/img/social/telegram-white.svg" alt="telegram" width="18" height="18">
                                </v-btn>
                            </a>
                        </li>
                    </ul>
                </div>
            </template>
        </v-navigation-drawer>

        <v-app-bar
            color="white"
            elevation="2"
            density="comfortable"
            class="px-3"
        >
            <v-app-bar-nav-icon @click="drawer = !drawer" />

            <v-toolbar-title class="text-h6 font-weight-bold"></v-toolbar-title>

            <v-spacer></v-spacer>

            <div class="d-flex align-center">
                <Link :href="route('admin.contacts.index')">
                    <v-btn icon color="black">
                        <v-badge color="secondary" :content="contactCount">
                            <v-icon>mdi-bell-ring</v-icon>
                        </v-badge>
                    </v-btn>
                </Link>
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
import { useDisplay } from 'vuetify' // اضافه شده
import FlashMessage from '@/Components/FlashMessage.vue'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'
import { isActive } from '@/utils/helpers.js'

const { mdAndUp } = useDisplay()
const drawer = ref(mdAndUp.value)

const page = usePage()
const confirmRef = ref(null)
const isLoading = ref(false)

const groupStates = ref({})
console.log(page.props.social)
/* From server props */
const menuItems = computed(() => page.props.menuItems || [])
const ticketCount = computed(() => page.props.ticketCount || 0)
const contactCount = computed(() => page.props.contactCount || 0)
const socialButtons = ref(page.props.social || {});

/* Global Confirm */
onMounted(() => {
    window.$confirm = async (message, options = {}) =>
        await confirmRef.value.open({
            msg: message,
            ttl: options.title || 'تأیید عملیات',
            color: options.color || 'red',
        })
})

watch(
    () => page.url,
    () => {
        menuItems.value.forEach(item => {
            const childActive = item.children?.some(c => isActive(c.route))
            if (childActive) {
                groupStates.value[item.title] = true
            }
        })

        // در موبایل بعد از کلیک روی لینک، سایدبار بسته شود
        if (!mdAndUp.value) {
            drawer.value = false
        }
    },
    { immediate: true }
)

const toggleGroup = (title, val) => {
    groupStates.value[title] = val
}

const logout = async () => {
    const confirmed = await window.$confirm('آیا برای خروج اطمینان دارید؟')
    if (!confirmed) return

    isLoading.value = true
    router.post(route('admin.logout'), {}, {
        onFinish: () => (isLoading.value = false)
    })
}
</script>

<style scoped>
.zo-main {
    background: rgba(245, 245, 245, 0.75);
    min-height: 100vh;
}

.v-navigation-drawer a {
    display: block;
    color: rgb(255, 255, 255);
    border-radius: 300px;
    transition: background 0.25s ease-in-out
}
.v-navigation-drawer a:hover {
    background: rgba(5, 70, 40, .5)
}

.v-list-item--active {
    background: rgba(5, 70, 40, .75);
    opacity: 1;
}

.v-list-item--active:not(.v-list-item--link) .v-list-item__overlay {
    opacity: 1;
}

.zo-social {
    margin: 0;
    padding: 7.5px 20px;
    background: rgba(5, 70, 40, .75);
    border-radius: 300px
}

.zo-social ul li {
    display: inline-block
}
</style>
