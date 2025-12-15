<template>

    <Head :title="`انقلاب | ${page.props.pageTitle}`" />
    <div>
        <v-app>
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
            <header>
                <div class="zo-header-section">
                    <v-container>
                        <v-row dense class="align-center">
                            <v-col cols="4" lg="2">
                                <Link :href="route('web.index')">
                                <img src="/assets/img/site/logo-header.svg" alt="" class="img-fluid">
                                </Link>
                            </v-col>
                            <v-col cols="12" lg="8" class="d-lg-block d-none">
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
                            <v-col cols="8" lg="2">
                                <div class="zo-actions">
                                    <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer" class="d-block d-lg-none"></v-app-bar-nav-icon>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </div>
            </header>
            <!-- Main Content -->
            <main class="flex-grow">
                <slot />
            </main>
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
                                                <a :href="item.url" :target="item.target">{{item.title}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="zo-menu" v-if="footer2?.length">
                                        <span>لینک‌های سریع</span>
                                        <ul>
                                            <li v-for="(item,index) in footer2" :key="item.id || index">
                                                <a :href="item.url" :target="item.target">{{item.title}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="zo-menu" v-if="footer3?.length">
                                        <span>ارتباط با مجموعه</span>
                                        <ul>
                                            <li v-for="(item,index) in footer3" :key="item.id || index">
                                                <a :href="item.url" :target="item.target">{{item.title}}</a>
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
                                                <img src="/assets/img/site/social-1.svg" alt="" class="img-fluid">
                                            </a>
                                        </li>
                                        <li>
                                            <a :href="social.eitaa" target="_blank">
                                                <img src="/assets/img/site/social-2.svg" alt="" class="img-fluid">
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
import { Head, Link, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { useDisplay } from 'vuetify';

const page = usePage();
const header = ref(page.props.header || []);
const footer1 = ref(page.props.footer1 || []);
const footer2 = ref(page.props.footer2 || []);
const footer3 = ref(page.props.footer3 || []);
const social = ref(page.props.social || {});

const drawer = ref(false);
const display = useDisplay();

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

.zo-header-section .zo-menu ul {
    display: flex;
    gap: 5px
}

.zo-header-section .zo-menu ul li {
    display: inline-block;
    position: relative;
}

.zo-header-section .zo-menu ul li:before {
    content: '';
    width: 1px;
    height: 15px;
    position: absolute;
    top: 2.5px;
    right: -2.5px;
    background: var(--Secondary)
}

.zo-header-section .zo-menu ul li:first-child:before {
    display: none
}

.zo-header-section .zo-menu ul li a {
    padding: 1.5px 10px;
    color: var(--White)
}

.zo-header-section .zo-menu ul li a:hover {
    color: var(--Secondary)
}

.zo-header-section .zo-actions {
    display: flex;
    justify-content: flex-end;
    gap: 5px
}

.zo-footer-section {
    width: 100%;
    display: inline-block;
    margin: 60px 0 0;
    padding: 5px 0;
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

    .zo-footer-section .zo-social {
        justify-content: center;
        margin: 0
    }
}

</style>
