<template>
    <div class="d-flex justify-end mb-4">
        <v-btn color="primary" prepend-icon="mdi-plus" @click="showDialog = true">
            افزودن محدودیت جدید
        </v-btn>
    </div>
    <v-table>
        <thead>
        <tr>
            <th class="text-center">توضیحات</th>
            <th class="text-center">نوع محدودیت</th>
            <th class="text-center">تاریخ اعمال</th>
            <th class="text-center">انقضا</th>
            <th class="text-center">عملیات</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in restrictions" :key="item.id">
            <td class="text-center">{{ item.reason || 'بدون توضیحات' }}</td>
            <td class="text-center">
                <span :class="`status-${item.type.value}`">{{ item.type.title }}</span>
            </td>
            <td class="text-center"><strong dir="ltr">{{ item.created_at.title }}</strong></td>
            <td class="text-center">
                <template v-if="item.expires_at.value">
                    <template v-if="isFutureDate(item.expires_at.value)">
                        <strong dir="ltr">{{ item.expires_at.title }}</strong>
                    </template>
                    <template v-else>
                        <span class="text-caption text-disabled">منقضی شده</span>
                    </template>
                </template>
                <template v-else>
                    <span>نامحدود</span>
                </template>
            </td>
            <td class="text-center">
                <v-btn
                    v-if="isFutureDate(item.expires_at.value) || item.expires_at.value == ''"
                    icon="mdi-cancel"
                    size="small"
                    color="error"
                    @click="removeRestriction(item.id)"
                    :loading="loadingItems[item.id]"
                    :disabled="loadingItems[item.id]"
                ></v-btn>
            </td>
        </tr>
        </tbody>
    </v-table>
    <v-dialog v-model="showDialog" max-width="500">
        <v-card>
            <v-card-title class="d-flex justify-space-between align-center">
                <strong>افزودن محدودیت جدید</strong>
                <v-btn icon flat size="small" @click="showDialog = false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text class="pb-0">
                <v-select
                    v-model="newRestriction.type"
                    variant="outlined"
                    :items="restrictionTypes"
                    label="نوع محدودیت"
                    item-title="title"
                    item-value="value"
                    class="mb-3"
                    prepend-inner-icon="mdi-cctv"
                    hide-details
                ></v-select>
                <v-textarea
                    v-model="newRestriction.reason"
                    variant="outlined"
                    label="دلیل محدودیت"
                    rows="3"
                    class="mb-3"
                    prepend-inner-icon="mdi-text"
                    hide-details
                ></v-textarea>
                <v-number-input
                    v-if="newRestriction.type === 'temporary_ban'"
                    v-model.number="newRestriction.days"
                    variant="outlined"
                    type="number"
                    min="1"
                    label="تعداد روزهای محدودیت"
                    class="mb-3"
                    prepend-inner-icon="mdi-calendar-today"
                    hide-details
                ></v-number-input>
            </v-card-text>
            <v-card-actions class="justify-center mb-3">
                <v-btn
                    color="primary"
                    variant="tonal"
                    type="submit"
                    :loading="isAdding"
                    @click="addRestriction"
                >
                    ثبت تغییرات
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script setup>
import {computed, ref} from "vue";
import {route} from "ziggy-js";
import {Link, router, useForm, usePage} from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
    restrictions: Object,
    restrictionTypes: Object,
});
const user = ref(props.user);
const page = usePage()
const restrictions = computed(() => page.props.restrictions.data)
const loadingItems = ref({})
const showDialog = ref(false);
const isAdding = ref(false);

const newRestriction = useForm({
    'type': '',
    'reason': '',
    'days': null,
});
const addRestriction = () => {
    newRestriction.post(route('admin.users.restrictions.store', {id: user.value.id}), {
        preserveScroll: true,
        onStart: () => isAdding.value = true,
        onSuccess: () => {
            // Only close dialog and reset form on success
            showDialog.value = false;
            newRestriction.reset();
        },
        onError: (errors) => {
            // Keep dialog open on error
            console.log('Validation errors:', errors);
        },
        onFinish: () => isAdding.value = false
    });
};
const removeRestriction = async (id) => {
    const ok = await $confirm("آیا مطمئن هستید؟");
    if (ok) {
        router.put(route('admin.users.restrictions.update', id), {
            preserveScroll: true,
            onStart: () => {
                loadingItems.value = {...loadingItems.value, [id]: true}
            },
            onSuccess: () => {
            },
            onFinish: () => {
                const newLoading = {...loadingItems.value}
                delete newLoading[id]
                loadingItems.value = newLoading
            }
        })
    }
}

const isFutureDate = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    return date > now;
};

</script>
