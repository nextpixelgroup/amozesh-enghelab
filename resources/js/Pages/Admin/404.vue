<template>
    <v-container>
        <v-row no-gutters>
            <v-col cols="12">
                <div class="zo-404-section">
                    <div class="zo-effect" data-tip="۴۰۴"></div>
                    <div class="zo-content">
                        <h2>متاسفانه صفحه ای یافت نشد.</h2>
                        <p>این محتوا یا پاک شده و از دسترس خارج شده است و یا لینک شما اشتباه می باشد.</p>
                    </div>
                    <v-btn @click="goBack" class="zo-action" color="primary" rounded="pill">
                        بازگشت به صفحه اصلی
                    </v-btn>
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>
<script setup>
import { Head, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
const goBack = () => {
    return router.visit(route('admin.courses.index'));
};

</script>
<style scoped>
.zo-404-section {
    width: 100%;
    display: inline-block;
    margin: 15px 0 0;
    padding: 130px 0 0
}

.zo-404-section .zo-effect {
    width: 100%;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    align-items: center;
    margin: 0 0 15px;
    position: relative;
    font-family: 'Estedad-Black';
    font-size: 12rem
}

.zo-404-section .zo-content {
    width: 100%;
    margin: 0 0 15px;
    padding: 60px 0 0;
    text-align: center
}

.zo-404-section .zo-content h2 {
    margin: 0;
    font-size: 1.5rem
}

.zo-404-section .zo-effect:before,
.zo-404-section .zo-effect:after {
    content: attr(data-tip);
    position: absolute;
    color: var(--Primary);
    -webkit-clip-path: polygon(0 100%, 100% 100%, 100% 40%, 0 60%);
    clip-path: polygon(0 100%, 100% 100%, 100% 40%, 0 60%);
    -webkit-animation: transition 1.5s infinite linear;
    animation: transition 1.5s infinite linear;
    -webkit-animation-delay: .5s;
    animation-delay: .5s
}

.zo-404-section .zo-effect:after {
    color: var(--Primary);
    -webkit-clip-path: polygon(0 0, 100% 0, 100% 36%, 0 56%);
    clip-path: polygon(0 0, 190% 0, 100% 36%, 0 56%);
    -webkit-animation: slide 1.5s infinite linear;
    animation: slide 1.5s infinite linear;
    -webkit-animation-delay: .5s;
    animation-delay: .5s
}

.zo-404-section .zo-action {
    height: 45px;
    display: flex;
    justify-content: center;
    margin: auto;
    padding: 0 35px
}

@-webkit-keyframes transition {
    0% {
        -webkit-transform: translateX(0);
        transform: translateX(0)
    }

    50% {
        -webkit-transform: translate(5px, -2%);
        transform: translate(5px, -2%)
    }
}

@keyframes transition {
    0% {
        -webkit-transform: translateX(0);
        transform: translateX(0)
    }

    50% {
        -webkit-transform: translate(5px, -2%);
        transform: translate(5px, -2%)
    }
}

@-webkit-keyframes movement {
    0% {
        -webkit-transform: translateX(0);
        transform: translateX(0)
    }

    50% {
        -webkit-transform: translate(-5px, 2%);
        transform: translate(-5px, 2%)
    }
}

@keyframes movement {
    0% {
        -webkit-transform: translateX(0);
        transform: translateX(0)
    }

    50% {
        -webkit-transform: translate(-5px, 2%);
        transform: translate(-5px, 2%)
    }
}

</style>
