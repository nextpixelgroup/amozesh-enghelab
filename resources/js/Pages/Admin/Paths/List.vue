<template>
    <Head title="Courses"/>
    <AdminLayout>
        <Head title="مسیرهای آموزشی"/>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-multicast"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">مسیرهای آموزشی</strong>
                            <span>مسیرهای آموزشی تاریخی مهدویت و انقلاب اسلامی ایران</span>
                        </div>
                    </div>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12 text-left">
                    <v-btn prepend-icon="$plus" class="zo-add" color="primary">افزودن مسیر</v-btn>
                </v-col>
            </v-row>
        </div>
        <v-expansion-panels multiple>
            <v-expansion-panel>
                <v-expansion-panel-title>
                    <v-btn
                        color="primary"
                        icon="mdi-drag"
                        width="25"
                        height="25"
                        size="small"
                        class="ml-2"
                    ></v-btn>
                    مسیر آموزشی انقلاب اسلامی ایران
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                    <v-autocomplete
                        clearable
                        chips
                        label="دوره‌های آموزشی"
                        :items="['دوره تخصصی مدیریت کاروان اربعین', 'گزیده محتوایی رزمایش های کشوری علمی فرهنگی میقات', 'دوره تخصصی ازدواج اسلامی - ویژه برادران', 'دوره آموزشی راهبردهای محتوایی راهیان نور', 'دوره عمومی فرماندهان بسیج طلاب', 'دوره تخصصی مدیریت اردوی راهیان نور']"
                        multiple
                        variant="outlined"
                    ></v-autocomplete>
                    <v-card variant="tonal" class="mb-1">
                        <v-card-text class="py-3">
                            <v-btn
                                color="primary"
                                icon="mdi-drag"
                                width="25"
                                height="25"
                                size="small"
                                class="ml-2"
                            ></v-btn>
                            دوره تخصصی مدیریت کاروان اربعین
                        </v-card-text>
                    </v-card>
                    <v-card variant="tonal" class="mb-1">
                        <v-card-text class="py-3">
                            <v-btn
                                color="primary"
                                icon="mdi-drag"
                                width="25"
                                height="25"
                                size="small"
                                class="ml-2"
                            ></v-btn>
                            گزیده محتوایی رزمایش های کشوری علمی فرهنگی میقات
                        </v-card-text>
                    </v-card>
                    <v-card variant="tonal" class="mb-1">
                        <v-card-text class="py-3">
                            <v-btn
                                color="primary"
                                icon="mdi-drag"
                                width="25"
                                height="25"
                                size="small"
                                class="ml-2"
                            ></v-btn>
                            دوره تخصصی ازدواج اسلامی - ویژه برادران
                        </v-card-text>
                    </v-card>
                    <v-card variant="tonal" class="mb-1">
                        <v-card-text class="py-3">
                            <v-btn
                                color="primary"
                                icon="mdi-drag"
                                width="25"
                                height="25"
                                size="small"
                                x class="ml-2"
                            ></v-btn>
                            دوره آموزشی راهبردهای محتوایی راهیان نور
                        </v-card-text>
                    </v-card>
                    <v-card variant="tonal" class="mb-1">
                        <v-card-text class="py-3">
                            <v-btn
                                color="primary"
                                icon="mdi-drag"
                                width="25"
                                height="25"
                                size="small"
                                class="ml-2"
                            ></v-btn>
                            دوره عمومی فرماندهان بسیج طلاب
                        </v-card-text>
                    </v-card>
                    <v-card variant="tonal" class="mb-1">
                        <v-card-text class="py-3">
                            <v-btn
                                color="primary"
                                icon="mdi-drag"
                                width="25"
                                height="25"
                                size="small"
                                class="ml-2"
                            ></v-btn>
                            دوره تخصصی مدیریت اردوی راهیان نور
                        </v-card-text>
                    </v-card>
                </v-expansion-panel-text>
            </v-expansion-panel>
            <v-expansion-panel>
                <v-expansion-panel-title>
                    <v-btn
                        color="primary"
                        icon="mdi-drag"
                        width="25"
                        height="25"
                        size="small"
                        class="ml-2"
                    ></v-btn>
                    دوره تخصصی مدیریت کاروان اربعین
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                    <v-expansion-panel-text>
                        <v-autocomplete
                            clearable
                            chips
                            label="دوره‌های آموزشی"
                            :items="['دوره تخصصی مدیریت کاروان اربعین', 'گزیده محتوایی رزمایش های کشوری علمی فرهنگی میقات', 'دوره تخصصی ازدواج اسلامی - ویژه برادران', 'دوره آموزشی راهبردهای محتوایی راهیان نور', 'دوره عمومی فرماندهان بسیج طلاب', 'دوره تخصصی مدیریت اردوی راهیان نور']"
                            multiple
                            variant="outlined"
                        ></v-autocomplete>
                        <v-card variant="tonal" class="mb-1">
                            <v-card-text class="py-3">
                                <v-btn
                                    color="primary"
                                    icon="mdi-drag"
                                    width="25"
                                    height="25"
                                    size="small"
                                    class="ml-2"
                                ></v-btn>
                                دوره تخصصی مدیریت کاروان اربعین
                            </v-card-text>
                        </v-card>
                        <v-card variant="tonal" class="mb-1">
                            <v-card-text class="py-3">
                                <v-btn
                                    color="primary"
                                    icon="mdi-drag"
                                    width="25"
                                    height="25"
                                    size="small"
                                    class="ml-2"
                                ></v-btn>
                                گزیده محتوایی رزمایش های کشوری علمی فرهنگی میقات
                            </v-card-text>
                        </v-card>
                        <v-card variant="tonal" class="mb-1">
                            <v-card-text class="py-3">
                                <v-btn
                                    color="primary"
                                    icon="mdi-drag"
                                    width="25"
                                    height="25"
                                    size="small"
                                    class="ml-2"
                                ></v-btn>
                                دوره تخصصی ازدواج اسلامی - ویژه برادران
                            </v-card-text>
                        </v-card>
                        <v-card variant="tonal" class="mb-1">
                            <v-card-text class="py-3">
                                <v-btn
                                    color="primary"
                                    icon="mdi-drag"
                                    width="25"
                                    height="25"
                                    size="small"
                                    x class="ml-2"
                                ></v-btn>
                                دوره آموزشی راهبردهای محتوایی راهیان نور
                            </v-card-text>
                        </v-card>
                        <v-card variant="tonal" class="mb-1">
                            <v-card-text class="py-3">
                                <v-btn
                                    color="primary"
                                    icon="mdi-drag"
                                    width="25"
                                    height="25"
                                    size="small"
                                    class="ml-2"
                                ></v-btn>
                                دوره عمومی فرماندهان بسیج طلاب
                            </v-card-text>
                        </v-card>
                        <v-card variant="tonal" class="mb-1">
                            <v-card-text class="py-3">
                                <v-btn
                                    color="primary"
                                    icon="mdi-drag"
                                    width="25"
                                    height="25"
                                    size="small"
                                    class="ml-2"
                                ></v-btn>
                                دوره تخصصی مدیریت اردوی راهیان نور
                            </v-card-text>
                        </v-card>
                    </v-expansion-panel-text>
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
    </AdminLayout>
</template>
<script setup>
import {Head} from '@inertiajs/vue3'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
</script>
