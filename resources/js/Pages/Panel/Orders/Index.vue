<template>
    <WebLayout>
        <PanelLayout>
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <v-table>
                            <thead>
                                <tr>
                                    <th class="text-center">ردیف</th>
                                    <th class="text-center">کد سفارش</th>
                                    <th class="text-center">جزییات سفارش</th>
                                    <th class="text-center">مبلغ</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td class="text-center">192469</td>
                                    <td>
                                        <ul>
                                            <li>
                                                <img src="/assets/img/sample/21.png">
                                                <div>
                                                    <strong>کتاب آیین رستگاری</strong>
                                                    <span>مصاحبات آیت الله طهرانی با برخی دوستان</span>
                                                </div>
                                            </li>
                                            <li>
                                                <img src="/assets/img/sample/22.png">
                                                <div>
                                                    <strong>کتاب آینده انقلاب اسلامی ایران</strong>
                                                    <span>چاپ جدید با ویرایش جدید و اضافات</span>
                                                </div>
                                            </li>
                                            <li>
                                                <img src="/assets/img/sample/23.png">
                                                <div>
                                                    <strong>کتاب آفرینش و انسان</strong>
                                                    <span>آفرینش از دیدگاه ادیان</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-col>
                </v-row>
            </v-container>
        </PanelLayout>
    </WebLayout>
</template>
<script setup>
import WebLayout from "@/Layouts/WebLayout.vue";
import PanelLayout from "@/Layouts/PanelLayout.vue";

</script>
<style scoped>
ul li {
    display: flex;
    align-items: center;
    gap: 5px
}

ul li img {
    max-width: 45px
}

ul li div strong {
    display: block;
    font-family: 'Estedad-Bold'
}

ul li div span {
    font-size: .90rem;
    color: var(--Sub)
}

</style>
