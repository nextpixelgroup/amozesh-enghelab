<template>
    <WebLayout>
        <PanelLayout>
            <div class="zo-dashboard-section">
                <div class="zo-books-section">
                    <v-container>
                        <div class="zo-header mb-3">
                            <v-row class="align-center">
                                <v-col cols="12">
                                    <div class="zo-info d-lg-flex d-sm-none">
                                        <div class="zo-icon elevation-4">
                                            <i class="mdi mdi-bookmark"></i>
                                        </div>
                                        <div class="zo-name">
                                            <strong class="d-block mb-1">علاقه‌مندی‌ها</strong>
                                            <span>در این بخش می توانید علاقه‌‌مندی‌های خود را مشاهده کنید.</span>
                                        </div>
                                    </div>
                                </v-col>
                            </v-row>
                        </div>
                        <div class="d-flex justify-center mb-8">
                            <Link
                                :href="route('panel.bookmarks.courses')"
                                class="text-decoration-none"
                            >
                            <v-btn
                                variant="tonal"
                                color=""
                                class="px-6 mx-2"
                                size="large"
                                rounded="lg"
                            >
                                <v-icon start>mdi-play-circle-outline</v-icon>
                                دوره‌ها
                            </v-btn>
                            </Link>
                            <Link
                                :href="route('panel.bookmarks.books')"
                                class="text-decoration-none"
                            >
                            <v-btn
                                variant="flat"
                                color="primary"
                                class="px-6 mx-2"
                                size="large"
                                rounded="lg"
                            >
                                <v-icon start>mdi-book-open-page-variant</v-icon>
                                کتب
                            </v-btn>
                            </Link>
                        </div>
                        <v-row dense class="align-center">
                            <v-col v-for="(books, index) in books.data" :key="index" cols="12" sm="6" md="4" lg="3">
                                <BookCard :book="books" />
                            </v-col>
                        </v-row>
                    </v-container>
                </div>
            </div>
        </PanelLayout>
    </WebLayout>
</template>
<script setup>
import WebLayout from "@/Layouts/WebLayout.vue";
import PanelLayout from "@/Layouts/PanelLayout.vue";
import BookCard from "@/Components/Web/Books/BookCard.vue";
import {route} from "ziggy-js";
import {Link} from "@inertiajs/vue3";
defineProps({
    books : {
        type: Object,
        default: []
    }
})
</script>
