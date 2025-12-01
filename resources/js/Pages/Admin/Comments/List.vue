<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-forum"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">نظرها</strong>
                            <span>در این بخش می توانید نظرات کتب و دوره ها را مدیریت کنید.</span>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </div>
        <v-card class="pa-3 mb-3 elevation-2">
            <v-row dense class="align-center">
                <v-col class="v-col-lg-3 v-col-12">
                    <v-select hide-details variant="outlined" density="compact" label="کتاب"
                              :items="['کتاب موعود در آیینه ادیان', 'کتاب جهان پس از ظهور']"></v-select>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12">
                    <v-select hide-details variant="outlined" density="compact" label="دوره"
                              :items="['دوره تاریخ دفاع مقدس', 'دوره تحلیل تاریخ معاصر ایران', 'دوره تحلیل تاریخ معاصر ایران']"></v-select>
                </v-col>
                <v-col class="v-col-lg-2 v-col-12">
                    <v-text-field hide-details variant="outlined" density="compact"
                                  label="جستجو کاربر"></v-text-field>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12">
                    <v-text-field hide-details variant="outlined" density="compact"
                                  label="جستجو نظر"></v-text-field>
                </v-col>
                <v-col class="v-col-lg-1 v-col-12">
                    <v-btn block variant="outlined" color="primary">جستجو</v-btn>
                </v-col>
            </v-row>
        </v-card>
        <v-card class="pa-3 elevation-2">
            <v-table>
                <thead>
                <tr>
                    <th class="text-center">ردیف</th>
                    <th class="text-center">کاربر</th>
                    <th class="text-center">بخش</th>
                    <th class="text-center">عنوان</th>
                    <th class="text-right">نظر</th>
                    <th class="text-center">تاریخ ارسال</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(item, index) in comments"
                    :key="index"
                    :class="{'zo-draft': item.is_approved === false}"
                >
                    <td class="text-center">{{ index+1 }}</td>
                    <td width="200px">
                        <div class="d-flex align-items-center ga-3">
                            <v-avatar>
                                <v-img :alt="item.user.avatar" :src="item.user.avatar"></v-img>
                            </v-avatar>
                            <div>
                                <strong class="d-block">{{ item.user.name }}</strong>
                                <small>{{ item.user.is_user ? item.user?.institution : item.user.email }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center"><span>{{ item.model.type }}</span></td>
                    <td class="text-center"><span><a :href="item.model.url" target="_blank">{{ item.model.title }}</a></span></td>
                    <td>{{ item.comment }}</td>
                    <td>
                        <span class="d-block">{{ item.date }}</span>
                        <small>{{ item.time }}</small>
                    </td>
                    <td>
                        <div class="d-flex ga-1 justify-center">
                            <v-btn
                                v-if="item.is_approved === false"
                                icon="mdi-check"
                                size="small"
                                color="primary"
                                @click="approve(item)"
                                :loading="approving[item.id]"
                                :disabled="approving[item.id]"
                            ></v-btn>
                            <v-btn
                                v-if="item.is_approved"
                                icon="mdi-reply"
                                size="small"
                                color="rgb(65, 75, 60)"
                                @click="openReplyDialog(item)"
                            ></v-btn>
                            <v-btn
                                icon="mdi-trash-can"
                                size="small"
                                color="secondary"
                                @click="destroy(item)"
                                :loading="destroying[item.id]"
                                :disabled="destroying[item.id]"
                            ></v-btn>
                        </div>
                    </td>
                </tr>
                </tbody>
            </v-table>
            <v-dialog v-model="dialog" max-width="400">
                <v-card class="pa-4 text-center">
                    <h3 class="mb-4">پاسخ به نظر</h3>
                    <v-textarea
                        v-model="replyBody"
                        :label="`پاسخ به ${replyTo}`"
                        variant="outlined"></v-textarea>
                    <v-btn
                        color="primary"
                        @click="reply(commentId)"
                        :loading="replying"
                        :disabled="replying"
                    >ثبت پاسخ</v-btn>
                </v-card>
            </v-dialog>
            <v-pagination
                rounded="circle"
                v-if="length > 1"
                v-model="currentPage"
                :length="length"
                @update:model-value="changePage"
                class="mt-4"
            />
        </v-card>
    </AdminLayout>
</template>

<script setup>
import {Head, router, usePage} from '@inertiajs/vue3'
import {computed, ref, watch} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
import {route} from "ziggy-js";

const props = defineProps({
    comments: Object
})
const comments = computed(() => props.comments.data);
const currentPage = computed( () => props.comments?.meta.current_page)
const length = computed( () => props.comments?.meta.last_page)
const {adminPageTitle} = usePageTitle('نظرات');
const commentId = ref(null)
const replyTo = ref(null)
const dialog = ref(false)
const approving = ref({})
const destroying = ref({})
const replying = ref(false)
const replyBody = ref(null)
const page = usePage();
const query = new URLSearchParams(page.url.split('?')[1])
const filters = ref({
    status: query.get('status') ?? '',
    teacher: query.get('teacher') ?? '',
    category: query.get('category') ?? '',
    search: query.get('search') ?? '',
});
const changePage = async (page) => {
    try {
        const query = {
            ...filters.value,  // Keep existing filters
            page  // Update only the page number
        };

        router.get(route('admin.comments.index'),
            query,
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['comments'],
                onSuccess: () => {
                    window.scrollTo({top: 0, behavior: 'smooth'});
                }
            }
        );
    } catch (error) {
        console.error('خطا در دریافت اطلاعات:', error);
    }
};
watch(() => props.comments, (newVal) => {
    comments.value = newVal.data || [];
    currentPage.value = newVal.meta?.current_page || 1;
});

const openReplyDialog = (item) => {
    dialog.value = true;
    commentId.value = item.id;
    replyTo.value = item.user.name;
}

const approve = (comment) => {
    router.post(route('admin.comments.approve',comment.id), {}, {
        onStart: () => {
            approving.value = {...approving.value, [comment.id]: true}
        },
        onSuccess: () => {

        },
        onFinish: () => {
            const newLoading = {...approving.value}
            delete newLoading[comment.id]
            approving.value = newLoading
        }
    });
}
const destroy = (comment) => {
    router.delete(route('admin.comments.destroy',comment.id), {}, {
        onStart: () => {
            destroying.value = {...destroying.value, [comment.id]: true}
        },
        onSuccess: () => {

        },
        onFinish: () => {
            const newLoading = {...destroying.value}
            delete newLoading[comment.id]
            destroying.value = newLoading
        }
    });
}

const reply = (commentId) => {
    router.post(route('admin.comments.reply',commentId), {body: replyBody.value}, {
        onStart: () => {
            replying.value = true;
        },
        onSuccess: () => {
            commentId.value = null;
            replyTo.value = null;
            replyBody.value = null;
            dialog.value = false;
            replyBody.value = '';
        },
        onFinish: () => {
            replying.value = false;
        }
    });
}

</script>

<style scoped>
.zo-draft {
    background: rgba(255, 245, 0, 0.15);
}
</style>
