<template>

    <Head :title="`انقلاب | ${page.props.pageTitle}`" />
    <div>
        <v-app>
            <!-- Drawer -->
            <v-navigation-drawer v-model="drawer" :location="display.mobile ? 'left' : undefined" temporary class="zo-drawer-section">
                <div class="zo-menu" v-if="header?.length">
                    <ul>
                        <li v-for="(item,index) in header" :key="item.id || index">
                            <a :href="item.url" :target="item.target">{{ item.title }}</a>
                            <ul v-if="item.children?.length">
                                <li v-for="(child, cIndex) in item.children" :key="child.id || cIndex">
                                    <a :href="child.url" :target="child.target">{{ child.title }}</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </v-navigation-drawer>
            <!-- Header -->
            <header>
                <div class="zo-header-section">
                    <v-container class="zo-container">
                        <v-row dense class="align-center">
                            <v-col cols="4" lg="2">
                                <Link :href="route('web.index')" class="zo-logo">
                                    <img src="/assets/img/site/logo-header.svg" alt="" class="img-fluid">
                                </Link>
                            </v-col>
                            <v-col cols="12" lg="7" class="d-lg-block d-none">
                                <div class="zo-menu" v-if="header?.length">
                                    <ul>
                                        <li v-for="(item,index) in header" :key="item.id || index">
                                            <a :href="item.url" :target="item.target">{{ item.title }}</a>
                                            <ul v-if="item.children?.length">
                                                <li v-for="(child, cIndex) in item.children" :key="child.id || cIndex">
                                                    <a :href="child.url" :target="child.target">{{ child.title }}</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </v-col>
                            <v-col cols="8" lg="3">
                                <div class="zo-actions">
                                    <!-- Search -->
                                    <div class="zo-search">
                                        <a href="#" @click.prevent="searchDialog = true">
                                            <img src="/assets/img/site/search.svg" alt="" class="img-fluid">
                                        </a>
                                    </div>
                                    <div class="zo-profile" v-if="isAuth">
                                        <a :href="route('panel.courses.index')">
                                            <img src="/assets/img/site/profile.svg" alt="" class="img-fluid">
                                        </a>
                                    </div>
                                    <div class="zo-panel d-none d-lg-block" v-else>
                                        <a :href="route('panel.login')">ورود/عضویت</a>
                                    </div>
                                    <div class="zo-cart" v-if="showCart">
                                        <a href="#">
                                            <img src="/assets/img/site/cart.svg" alt="" class="img-fluid">
                                        </a>
                                    </div>
                                    <v-app-bar-nav-icon variant="text" class="d-block d-lg-none" @click.stop="drawer = !drawer" />
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </div>
            </header>
            <!-- Search Dialog -->
            <v-dialog v-model="searchDialog" max-width="500">
                <div class="zo-search-section">
                    <div class="zo-close">
                        <v-btn icon size="small" @click="searchDialog = false">
                            <i class="mdi mdi-close"></i>
                        </v-btn>
                    </div>
                    <v-card class="pa-5">
                        <v-form class="zo-form" @submit.prevent="search">
                            <v-text-field v-model="searchText" variant="outlined" color="primary" hide-details label="جستجو دوره‌های آموزشی"></v-text-field>
                            <v-btn
                                type="submit"
                                flat
                                size="large"
                                color="primary"
                                class="zo-button"
                                :disabled="isLoading"
                                :loading="isLoading"
                            >جستجو</v-btn>
                        </v-form>
                    </v-card>
                </div>
            </v-dialog>
            <!-- Main -->
            <main class="flex-grow">
                <slot />
            </main>
            <!-- Footer -->
            <footer>
                <div class="zo-footer-section">
                    <v-container>
                        <v-row>
                            <v-col cols="12" lg="5">
                                <div class="zo-about">
                                    <img src="/assets/img/site/logo-footer.svg" alt="" class="img-fluid">
                                    <p>
                                        متن توضیحات مرتبط با خانه انقلاب اسلامی که توضیحات مختصری در مورد فعالیت‌ها،
                                        برنامه‌ها و دوره‌های برگزار شده در این مجموعه است در این قسمت قرار می‌گیرد.
                                    </p>
                                </div>
                            </v-col>
                            <v-col cols="12" lg="5" class="d-lg-block d-none">
                                <div class="zo-menus">
                                    <div class="zo-menu" v-if="footer1?.length">
                                        <span>دوره‌ها و فروشگاه</span>
                                        <ul>
                                            <li v-for="(item,index) in footer1" :key="item.id || index">
                                                <a :href="item.url" :target="item.target">{{ item.title }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="zo-menu" v-if="footer2?.length">
                                        <span>لینک‌های سریع</span>
                                        <ul>
                                            <li v-for="(item,index) in footer2" :key="item.id || index">
                                                <a :href="item.url" :target="item.target">{{ item.title }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="zo-menu" v-if="footer3?.length">
                                        <span>ارتباط با مجموعه</span>
                                        <ul>
                                            <li v-for="(item,index) in footer3" :key="item.id || index">
                                                <a :href="item.url" :target="item.target">{{ item.title }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </v-col>
                            <v-col cols="12" lg="2">
                                <div class="zo-social">
                                    <ul>
                                        <li>
                                            <a :href="social.soroush" target="_blank">
                                                <img src="/assets/img/site/social-1.svg" alt="">
                                            </a>
                                        </li>
                                        <li>
                                            <a :href="social.eitaa" target="_blank">
                                                <img src="/assets/img/site/social-2.svg" alt="">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </div>
                <div class="zo-copyright-section">
                    تمامی حقوق برای خانه انقلاب اسلامی محفوظ می‌باشد. ۱۴۰۴
                </div>
            </footer>
        </v-app>
    </div>
    <FlashMessage />
</template>
<script setup>
import { ref } from 'vue'
import FlashMessage from "@/Components/FlashMessage.vue";
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { useDisplay } from 'vuetify';

const page = usePage();

const header = ref(page.props.header || []);
const footer1 = ref(page.props.footer1 || []);
const footer2 = ref(page.props.footer2 || []);
const footer3 = ref(page.props.footer3 || []);
const social = ref(page.props.social || {});
const showCart = ref(page.props.showCart || false);
const isAuth = ref(page.props.isAuth || false);

const drawer = ref(false);
const searchDialog = ref(false);
const isLoading = ref(false);
const display = useDisplay();
const searchText = ref('');
const search = () => {
    isLoading.value = true
    window.location.href = route('web.courses.index', { search: searchText.value });
}
</script>
<style scoped>

.zo-drawer-section ul {
    padding: 15px
}

.zo-drawer-section ul li {
    width: 100%;
    display: inline-block
}

.zo-drawer-section ul li a {
    display: block;
    padding: 7.5px 10px;
    border-radius: .25rem
}

.zo-drawer-section ul li a:hover {
    background: rgb(235, 235, 235);
}

.zo-header-section {
    width: 100%;
    display: inline-block;
    background: var(--Primary) url(/public/assets/img/site/footer-pattern.png);
    color: var(--White)
}

.zo-header-section .zo-logo {
    display: flex
}

.zo-header-section .zo-container .zo-menu ul {
    display: flex;
    gap: 5px
}

.zo-header-section .zo-container .zo-menu ul li {
    display: inline-block;
    position: relative;
}

.zo-header-section .zo-container .zo-menu ul li:before {
    content: '';
    width: 1px;
    height: 15px;
    position: absolute;
    top: 2.5px;
    right: -2.5px;
    background: var(--Secondary)
}

.zo-header-section .zo-container .zo-menu ul li:first-child:before {
    display: none
}

.zo-header-section .zo-container .zo-menu ul li a {
    padding: 1.5px 10px;
    color: var(--White)
}

.zo-header-section .zo-container .zo-menu ul li a:hover {
    color: var(--Secondary)
}

.zo-header-section .zo-container .zo-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 5px
}

.zo-header-section .zo-container .zo-actions .zo-cart {
    margin: 0 0 0 10px
}

.zo-header-section .zo-container .zo-actions .zo-cart a,
.zo-header-section .zo-container .zo-actions .zo-search a,
.zo-header-section .zo-container .zo-actions .zo-profile a {
    display: flex;
    position: relative;
}

.zo-header-section .zo-container .zo-actions .zo-search a {
    padding: 0 0 0 15px
}

.zo-header-section .zo-container .zo-actions .zo-search a:before {
    content: '';
    width: 1px;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    background: var(--Secondary);
}

.zo-header-section .zo-container .zo-actions .zo-cart a {
    padding: 0 15px 0 0
}

.zo-header-section .zo-container .zo-actions .zo-cart a:before {
    content: '';
    width: 1px;
    height: 100%;
    position: absolute;
    top: 0;
    right: 0;
    background: var(--Secondary);
}

.zo-header-section .zo-container .zo-actions .zo-profile a {
    padding: 0 10px
}

.zo-header-section .zo-container .zo-actions .zo-cart a img,
.zo-header-section .zo-container .zo-actions .zo-search a img {
    width: 20px
}

.zo-header-section .zo-container .zo-actions .zo-profile a img {
    width: 15px
}

.zo-header-section .zo-container .zo-actions .zo-panel {
    padding: 0 10px
}

.zo-header-section .zo-container .zo-actions .zo-panel a {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5px 15px;
    background: rgba(255, 255, 255, .25);
    color: var(--White);
    border: 1px solid var(--White);
    border-radius: 300px
}

@media (max-width: 1280px) {

    .zo-header-section .zo-container {
        padding: 10px 5px
    }

    .zo-header-section .zo-container .zo-actions .zo-cart {
        margin: 0 10px
    }
}

.zo-search-section .zo-close {
    position: absolute;
    top: -15px;
    left: -15px;
    z-index: 15
}

.zo-search-section .zo-form {
    position: relative
}

.zo-search-section .zo-form .zo-button {
    position: absolute;
    top: 5.5px;
    left: 5px
}

.zo-footer-section {
    width: 100%;
    display: inline-block;
    margin: 60px 0 0;
    padding: 5px 0 25px;
    background: var(--Primary) url(/public/assets/img/site/footer-pattern.png);
    color: var(--White)
}

.zo-footer-section .zo-about {
    display: flex;
    align-items: center;
    gap: 15px
}

.zo-footer-section .zo-about p {
    margin: 0;
    text-align: justify
}

.zo-footer-section .zo-menus {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin: 30px 0 0
}

.zo-footer-section .zo-menus .zo-menu span {
    display: block;
    margin: 0 0 5px;
    font-family: 'Estedad-Medium';
}

.zo-footer-section .zo-menus .zo-menu ul li {
    width: 100%;
    display: inline-block;
    padding: 2.5px 35px 2.5px 0;
    position: relative
}

.zo-footer-section .zo-menus .zo-menu ul li:before {
    content: '';
    width: 30px;
    height: 15px;
    position: absolute;
    top: 7.5px;
    right: 0;
    background: var(--Primary) url(/public/assets/img/site/footer-menu.svg);
    background-size: contain
}

.zo-footer-section .zo-menus .zo-menu ul li a {
    color: var(--White)
}

.zo-footer-section .zo-social {
    display: flex;
    justify-content: flex-end;
    margin: 95px 0 0
}

.zo-footer-section .zo-social ul {
    display: flex;
    gap: 20px
}

.zo-footer-section .zo-social ul li {
    display: inline-block;
    position: relative
}

.zo-footer-section .zo-social ul li:before {
    content: '';
    width: 5px;
    height: 5px;
    position: absolute;
    top: 10px;
    right: -13.5px;
    background: var(--Secondary);
    border-radius: 50%
}

.zo-footer-section .zo-social ul li:first-child:before {
    display: none
}

.zo-copyright-section {
    width: 100%;
    padding: 5px 0;
    background: var(--Secondary);
    color: var(--Primary);
    text-align: center
}

@media (max-width: 960px) {

    .zo-footer-section {
        padding: 30px 0
    }

    .zo-footer-section .zo-about {
        flex-direction: column;
        text-align: center
    }

    .zo-footer-section .zo-about p {
        text-align: center
    }

    .zo-footer-section .zo-social {
        justify-content: center;
        margin: 0
    }
}

</style>
