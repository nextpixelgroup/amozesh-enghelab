import { computed } from 'vue'

export default function usePageTitle(title = '') {
    const adminTitle = 'پنل مدیریت'
    const adminPageTitle = computed(() =>
        title ? `${adminTitle} | ${title}` : adminTitle
    )

    const webTitle = 'انقلاب'
    const webPageTitle = computed(() =>
        title ? `${webTitle} | ${title}` : webTitle
    )

    const panelTitle = 'پنل کاربری'
    const panelPageTitle = computed(() =>
        title ? `${panelTitle} | ${title}` : panelTitle
    )

    return { adminPageTitle, webPageTitle, panelPageTitle}
}
