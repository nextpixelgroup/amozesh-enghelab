<template>
    <WebLayout>
        <PanelLayout>
            <v-container>
                <v-row class="justify-center">
                    <v-col cols="12">
                        <div class="zo-header mb-3">
                            <div class="zo-info d-lg-flex d-sm-none">
                                <div class="zo-icon elevation-4">
                                    <i class="mdi mdi-phone-in-talk"></i>
                                </div>
                                <div class="zo-name">
                                    <strong class="d-block mb-1">ارتباط با پشتیبان خانه انقلاب اسلامی</strong>
                                    <span>در صورت بروز هرگونه مشکل در خرید آنلاین کتاب یا ثبت نام دوره آموزشی می توانید با پشتیبان ما در تماس باشید.</span>
                                </div>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="12" lg="9">
                        <p class="mb-3">
                            <v-text-field
                                v-model="form.subject"
                                label="موضوع درخواست"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-text-short"
                                hide-details
                                type="text"
                            />
                        </p>
                        <p class="mb-3">
                            <v-textarea
                                v-model="form.message"
                                label="متن..."
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-text"
                                hide-details
                                type="textarea"
                            ></v-textarea>
                        </p>
                        <p class="text-end">
                            <v-btn
                                color="primary"
                                type="submit"
                                @click="submitTicket"
                            >
                                ارسال درخواست
                            </v-btn>
                        </p>
                    </v-col>
                </v-row>
            </v-container>
        </PanelLayout>
    </WebLayout>
</template>
<script setup>
import WebLayout from "@/Layouts/WebLayout.vue";
import PanelLayout from "@/Layouts/PanelLayout.vue";
import {ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
const isLoading = ref(false)
const form = useForm({
    subject: '',
    message: '',
});
const submitTicket = () => {
    form.post(route('panel.supports.store'), {
        preserveState: true,
        preserveScroll: true,
        onStart: () => {
            isLoading.value = true;
        },
        onSuccess: () => {
            isLoading.value = false;
            form.reset();
        },
        onFinish: () => {
            isLoading.value = false;
        },
        onError: () => {
            isLoading.value = false;
        }
    })
}

</script>
