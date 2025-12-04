<template>

    <Head title="مدرسین" />
    <WebLayout>
        <div class="zo-prof-section">
            <v-container>
                <v-row class="align-center">
                    <v-col cols="12">
                        <div class="zo-title-section">
                            <img src="/assets/img/site/right-primary.svg" alt="">
                            <strong>مدرسین خانه انقلاب</strong>
                            <img src="/assets/img/site/left-primary.svg" alt="">
                        </div>
                    </v-col>
                    <v-col lg="3" sm="6" cols="12">
                        <a href="#" class="zo-prof">
                            <figure>
                                <img src="/assets/img/sample/41.png" alt="">
                            </figure>
                            <strong>دکتر یزدیان</strong>
                            <span>دکترا دین و ادیان</span>
                            <small>درباره مدرس</small>
                        </a>
                    </v-col>
                    <v-col lg="3" sm="6" cols="12">
                        <a href="#" class="zo-prof">
                            <figure>
                                <img src="/assets/img/sample/42.png" alt="">
                            </figure>
                            <strong>دکتر یزدیان</strong>
                            <span>دکترا دین و ادیان</span>
                            <small>درباره مدرس</small>
                        </a>
                    </v-col>
                    <v-col lg="3" sm="6" cols="12">
                        <a href="#" class="zo-prof">
                            <figure>
                                <img src="/assets/img/sample/43.png" alt="">
                            </figure>
                            <strong>دکتر یزدیان</strong>
                            <span>دکترا دین و ادیان</span>
                            <small>درباره مدرس</small>
                        </a>
                    </v-col>
                    <v-col lg="3" sm="6" cols="12">
                        <a href="#" class="zo-prof">
                            <figure>
                                <img src="/assets/img/sample/44.png" alt="">
                            </figure>
                            <strong>دکتر یزدیان</strong>
                            <span>دکترا دین و ادیان</span>
                            <small>درباره مدرس</small>
                        </a>
                    </v-col>
                    <v-col lg="3" sm="6" cols="12">
                        <a href="#" class="zo-prof">
                            <figure>
                                <img src="/assets/img/sample/44.png" alt="">
                            </figure>
                            <strong>دکتر یزدیان</strong>
                            <span>دکترا دین و ادیان</span>
                            <small>درباره مدرس</small>
                        </a>
                    </v-col>
                    <v-col lg="3" sm="6" cols="12">
                        <a href="#" class="zo-prof">
                            <figure>
                                <img src="/assets/img/sample/1.jpg" alt="">
                            </figure>
                            <strong>دکتر یزدیان</strong>
                            <span>دکترا دین و ادیان</span>
                            <small>درباره مدرس</small>
                        </a>
                    </v-col>
                    <v-col lg="3" sm="6" cols="12">
                        <a href="#" class="zo-prof">
                            <figure>
                                <img src="/assets/img/sample/2.jpg" alt="">
                            </figure>
                            <strong>دکتر یزدیان</strong>
                            <span>دکترا دین و ادیان</span>
                            <small>درباره مدرس</small>
                        </a>
                    </v-col>
                    <v-col lg="3" sm="6" cols="12">
                        <a href="#" class="zo-prof">
                            <figure>
                                <img src="/assets/img/sample/3.jpg" alt="">
                            </figure>
                            <strong>دکتر یزدیان</strong>
                            <span>دکترا دین و ادیان</span>
                            <small>درباره مدرس</small>
                        </a>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>
<script setup>
import { Head } from "@inertiajs/vue3";
import WebLayout from "@/Layouts/WebLayout.vue";

</script>
<style scoped>
.zo-prof-section {
    width: 100%;
    display: inline-block;
    padding: 30px 0;
    text-align: center
}

.zo-prof-section .zo-title-section {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin: 0 0 15px;
    padding: 0 15px;
    font-size: 1.25rem
}

.zo-prof-section .zo-title-section strong {
    font-family: 'Estedad-Medium';
    font-weight: normal
}

.zo-prof-section .zo-prof {
    display: block;
    margin: 0 0 10px
}

.zo-prof-section .zo-prof figure img {
    width: 200px;
    height: 200px;
    display: block;
    margin: 0 auto 15px;
    border-radius: 50%;
    object-fit: cover;
    transition: all 0.25s ease-in-out
}

.zo-prof-section .zo-prof strong {
    display: block;
    margin: 0 0 5px
}

.zo-prof-section .zo-prof span {
    display: block;
    margin: 0 0 10px;
    color: var(--Sub)
}

.zo-prof-section .zo-prof small {
    max-width: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
    padding: 5px 10px;
    background: rgba(5, 105, 60, .05);
    color: var(--Primary);
    border-radius: 300px;
    transition: all 0.25s ease-in-out
}

.zo-prof-section .zo-prof:hover figure img {
    box-shadow: 0 5px 15px rgba(0, 0, 0, .085)
}

.zo-prof-section .zo-prof:hover small {
    background: var(--Primary);
    color: var(--White)
}

</style>
