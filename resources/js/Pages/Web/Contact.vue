<template>
    <WebLayout title="ContactUs">
        <div class="zo-contact-section">
            <v-container>
                <v-row dense>
                    <v-col cols="12">
                        <div class="zo-breadcrumbs-section">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="#">خانه</a>
                                    </li>
                                    <li>
                                        <span>درباره ما</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row dense>
                    <v-col lg="5" cols="12">
                        <div class="zo-content">
                            <span>{{contact.title}}</span>
                            <div v-html="contact.content"></div>
                            <ul class="zo-social">
                                <li>
                                    <v-btn color="primary">
                                        <img src="/assets/img/aparat.svg" width="20" height="20" />
                                    </v-btn>
                                </li>
                                <li>
                                    <v-btn color="primary">
                                        <img src="/assets/img/bale.svg" width="20" height="20" />
                                    </v-btn>
                                </li>
                                <li>
                                    <v-btn color="primary">
                                        <img src="/assets/img/eitaa.svg" width="20" height="20" />
                                    </v-btn>
                                </li>
                                <li>
                                    <v-btn color="primary">
                                        <img src="/assets/img/soroosh.svg" width="20" height="20" />
                                    </v-btn>
                                </li>
                            </ul>
                        </div>
                    </v-col>
                    <v-col lg="7" cols="12">
                        <v-row dense>
                            <v-col lg="6" cols="12">
                                <v-text-field
                                    v-model="form.name"
                                    hide-details
                                    class="mb-2"
                                    label="نام و نام خانوادگی خود را وارد کنید"
                                    variant="outlined"
                                    prepend-inner-icon="mdi-account-circle-outline"
                                />
                                <v-text-field
                                    v-model="form.email"
                                    prepend-inner-icon="mdi-email-outline"
                                    hide-details
                                    class="my-4"
                                    label="ایمیل خود را وارد کنید"
                                    variant="outlined"
                                />
                                <v-text-field
                                    v-model="form.mobile"
                                    prepend-inner-icon="mdi-phone-in-talk-outline"
                                    hide-details
                                    class="mb-2"
                                    label="شماره موبایل خود را وارد کنید"
                                    variant="outlined"
                                />
                            </v-col>
                            <v-col lg="6" cols="12" class="pr-3">
                                <v-textarea
                                    v-model="form.message"
                                    prepend-inner-icon="mdi-text"
                                    hide-details rows="7"
                                    label="پیام خود را بنویسید"
                                    variant="outlined"
                                />
                            </v-col>
                            <v-col cols="12">
                                <div class="col-12">
                                    <div class="text-center">
                                        <v-btn
                                            variant="elevated"
                                            color="primary"
                                            class="zo-send"
                                            height="45"
                                            @click="sendMessage"
                                            :disabled="isLoading"
                                            :loading="isLoading"
                                        >ارسال پیام</v-btn>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row dense>
                    <v-col cols="12">
                        <div class="zo-map-section">
                            <div class="zo-info">
                                <v-row dense>
                                    <v-col lg="4" cols="12">
                                        <i class="mdi mdi-map-marker"></i>
                                        <strong>دفتر مرکزی</strong>
                                        <span>{{contact.meta?.address}}</span>
                                    </v-col>
                                    <v-col lg="4" cols="12">
                                        <i class="mdi mdi-phone-in-talk-outline"></i>
                                        <strong>تلفن تماس</strong>
                                        <div>
                                            <a :href="`tel:${contact.meta?.tel}`">{{contact.meta?.tel}}</a>
                                        </div>
                                    </v-col>
                                    <v-col lg="4" cols="12">
                                        <i class="mdi mdi-email-outline"></i>
                                        <strong>ایمیل</strong>
                                        <span>{{contact.meta?.email}}</span>
                                    </v-col>
                                </v-row>
                            </div>
                            <div class="zo-map">
                                <img :src="contact.meta?.mapImage?.url" alt="" class="img-fluid">
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>
<script setup>
import WebLayout from "@/Layouts/WebLayout.vue";
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
const props = defineProps({
    contact: Object
})
const isLoading = ref(false);
const contact = ref(props.contact.data)

const form = useForm({
    name: '',
    email: '',
    mobile: '',
    message: '',
});
const sendMessage = () => {
    try {
        form.post(route('web.contact.store'), {
            preserveScroll:true,
            preserveState:true,
            onStart: () => {
                isLoading.value = true;
            },
            onSuccess: () => {
                form.reset();
                isLoading.value = false;
            },
            onError: () => {
                isLoading.value = false;
            },
        })
    }
    catch (error) {
        isLoading.value = false;
    }
}

</script>
<style scoped>
.zo-contact-section .zo-content {
    padding: 0 0 0 15px;
}

.zo-contact-section .zo-content span {
    color: var(--Sub);
}

.zo-contact-section .zo-content :deep(h1) {
    display: block;
    margin: 5px 0 10px;
    font-size: 1.5rem
}

.zo-contact-section .zo-content :deep(p) {
    text-align: justify
}

.zo-contact-section .zo-content .zo-social {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin: 15px 0
}

.zo-contact-section .zo-content .zo-social li {
    display: inline-block;
}

.zo-contact-section .zo-content .zo-social li .v-btn {
    min-width: 45px;
    min-height: 45px;
    padding: 0;
    border-radius: 50%
}

.zo-contact-section .zo-send {
    padding: 0 35px;
    border-radius: 300px
}

.zo-map-section {
    width: 100%;
    display: inline-block;
    margin: 30px 0 0;
    padding: 30px;
    background: rgba(5, 105, 60, .025);
    text-align: center;
    border-radius: 20px
}

.zo-map-section .zo-info {
    width: 100%;
    display: inline-block;
    margin: 0 0 30px;
}

.zo-map-section .zo-info i {
    font-size: 1.5rem;
    color: var(--Primary)
}

.zo-map-section .zo-info strong {
    display: block;
    padding: 5px 0;
}

.zo-map-section .zo-info span {
    color: var(--Sub);
}

.zo-map-section .zo-info a {
    display: inline-block;
    padding: 0 5px;
    color: var(--Sub);
}

.zo-map-section .zo-info a:hover {
    color: var(--Primary);
}

.zo-map-section .zo-map {
    overflow: hidden;
    border-radius: 15px;
}

@media (max-width: 1200px) {

    .zo-contact-section .zo-content {
        padding: 0
    }
}
</style>