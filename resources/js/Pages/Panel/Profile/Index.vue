<template>
    <WebLayout>
        <PanelLayout>
            
        </PanelLayout>
       <div class="zo-dashboard-section">
            <div class="zo-nav">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="#" class="zo-active">
                                            <i class="mdi mdi-account-circle"></i>
                                            <span>پروفایل کاربری</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="mdi mdi-human-male-board"></i>
                                            <span>آموزش‌های من</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="mdi mdi-bookmark"></i>
                                            <span>علاقه‌مندی‌ها</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="mdi mdi-cart"></i>
                                            <span>سفارش‌ها</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="mdi mdi-phone-in-talk"></i>
                                            <span>ارتباط پشتیبان</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <v-container>
               <v-row dense>
                  <v-col cols="12" md="6" lg="3">
                    <v-text-field
                        label="نام"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-account"
                        hide-details
                        type="text"
                    />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field
                        label="نام خانوادگی"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-account-circle"
                        hide-details
                        type="text"
                    />
                </v-col>
                  <v-col cols="12" md="6" lg="3">
                    <v-select
                        label="جنسیت"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-gender-male"
                        hide-details
                    />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field
                        label="تلفن همراه"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-cellphone-settings"
                        hide-details
                        type="text"
                    />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field
                        type="text"
                        variant="outlined"
                        density="comfortable"
                        label="تلفن ثابت"
                        prepend-inner-icon="mdi-phone-classic"
                        hide-details
                    />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field
                        label="ایمیل"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-email"
                        hide-details
                        type="email"
                    />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field
                        type="text"
                        label="کدملی"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-badge-account"
                        hide-details
                    />
                </v-col>
                <v-col cols="12" md="6" lg="6">
                    <v-text-field
                        label="آدرس"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-map-marker-radius"
                        hide-details
                        type="text"
                    />
                </v-col>
                 <v-col cols="12" md="3" lg="3">
                    <v-text-field
                        type="text"
                        variant="outlined"
                        density="comfortable"
                        label="کدپستی"
                        prepend-inner-icon="mdi-home-variant"
                        hide-details
                    />
                </v-col>
               </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>
<script setup>
import WebLayout from "@/Layouts/WebLayout.vue";
import PanelLayout from "@/Layouts/PanelLayout.vue";
</script>
