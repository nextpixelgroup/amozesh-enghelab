<template>
    <WebLayout>
        <div class="zo-home-books-section">
            <v-container>
                <v-row dense>
                    <v-col cols=12>
                        <div class="zo-breadcrumbs-section">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="#">خانه</a>
                                    </li>
                                    <li>
                                        <span>فروشگاه کتاب</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <BookSection1
                :books="section1.data"
                title="فروش ویژه آثار آیت الله خامنه‌ای"
                description="لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد."
            />
            <BookSection2
                :books="section2.data"
                title="آثار برگزیده"
            />
            <BookSection3
                :books="section3.data"
                title="آثار برگزیده"
                :more-url="section3.moreUrl"
            />
            <BookSection4
                :books="section4.data"
            />
            <BookSection5
                :books="section5.data"
                title="کتب معارف اسلامی"
                :more-url="section5.moreUrl"
            />
        </div>
    </WebLayout>
</template>
<script setup>
import WebLayout from "@/Layouts/WebLayout.vue";
import BookSection1 from "@/Components/Web/Books/BookSection1.vue";
import BookSection2 from "@/Components/Web/Books/BookSection2.vue";
import BookSection3 from "@/Components/Web/Books/BookSection3.vue";
import BookSection4 from "@/Components/Web/Books/BookSection4.vue";
import BookSection5 from "@/Components/Web/Books/BookSection5.vue";
import {route} from "ziggy-js";

defineProps({
    section1: Object,
    section2: Object,
    section3: Object,
    section4: Object,
    section5: Object,
})

</script>
