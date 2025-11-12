import { computed } from 'vue'

export default function usePageTitle(title = '') {
    const appName = 'پنل مدیریت'
    const adminPageTitle = computed(() =>
        title ? `${appName} | ${title}` : appName
    )

    return { adminPageTitle }
}
