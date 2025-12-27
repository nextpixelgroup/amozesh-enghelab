<template>
    <SettingLayout>
        <SettingImages
            :logos="logos"
        />
        <SettingSliderManager
            :slides="slides"
            @saved="handleSlidesUpdated"
            @deleted="handleSlidesUpdated"
        />
        <SettingSocialLinksManager
            :social-links="social"
            @saved="handleSocialLinksUpdated"
        />
    </SettingLayout>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import SettingLayout from '@/Components/Admin/SettingLayout.vue'
import SettingSliderManager from '@/Components/Admin/Settings/SettingSliderManager.vue'
import SettingSocialLinksManager from "@/Components/Admin/Settings/SettingSocialLinksManager.vue";
import SettingImages from "@/Components/Admin/Settings/SettingImages.vue";
import {useTemplateRefsList} from "@vueuse/core";

const page = usePage()
const slides = page.props.slides ?? []
const social = page.props.social ?? { soroush: '', eitaa: '' }
const logos = page.props.logos ?? { header: { id: null, url: null }, footer: { id: null, url: null } }

const handleSlidesUpdated = (newSlides) => {
    console.log('Slides updated:', newSlides)
    // در صورت نیاز state والد را هم به‌روزرسانی کنید.
}
const handleSocialLinksUpdated = (newLinks) => {
    console.log('Social links updated:', newLinks)
}
</script>
