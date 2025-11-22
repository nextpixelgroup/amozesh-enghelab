<template>
    <!-- دیالوگ پاسخ -->
    <v-dialog v-model="replyDialog" max-width="500">
        <v-card>
            <v-card-title>ثبت پاسخ</v-card-title>
            <v-card-text>
                <v-textarea
                    v-model="replyText"
                    variant="outlined"
                    rows="4"
                    placeholder="پاسخ خود را بنویسید..."
                />
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text @click="replyDialog = false">انصراف</v-btn>
                <v-btn color="primary" @click="submitReply">ثبت پاسخ</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <div class="zo-space" id="comments">
        <div class="zo-comments-section">
            <strong class="zo-title">نظرات کاربران</strong>
            <div class="zo-label">ثبت دیدگاه</div>

            <!-- فرم ارسال نظر -->
            <v-row dense>
                <v-col cols="12" lg="6">
                    <v-text-field
                        v-if="!user"
                        v-model="comment.name"
                        hide-details
                        variant="outlined"
                        density="comfortable"
                        placeholder="* نام و نام خانوادگی"
                        prepend-inner-icon="mdi-account-circle"
                    />
                </v-col>
                <v-col cols="12" lg="6">
                    <v-text-field
                        v-if="!user"
                        v-model="comment.email"
                        hide-details
                        variant="outlined"
                        density="comfortable"
                        placeholder="پست الکترونیک"
                        prepend-inner-icon="mdi-email"
                    />
                </v-col>
                <v-col cols="12">
                    <v-textarea
                        v-model="comment.body"
                        hide-details
                        variant="outlined"
                        density="comfortable"
                        rows="3"
                        placeholder="* سوال یا دیدگاه خود را بنویسید"
                        prepend-inner-icon="mdi-text-long"
                    ></v-textarea>
                </v-col>
                <v-col cols="12" class="text-end">
                    <v-btn flat color="secondary" @click="submitComment" :loading="submitting" :disabled="submitting">ارسال دیدگاه</v-btn>
                </v-col>
            </v-row>

            <!-- لیست نظرات -->
            <v-row dense class="mt-4">
                <v-col cols="12">
                    <!-- لودینگ در زمان دریافت کامنت‌ها -->
                    <div v-if="loadingComments" class="text-center pa-5">
                        <v-progress-circular indeterminate color="primary"></v-progress-circular>
                        <div class="mt-2 text-grey">در حال بارگذاری نظرات...</div>
                    </div>

                    <!-- نمایش نظرات -->
                    <div v-else>
                        <div class="zo-stats">
                            <!-- فرض بر این است که تعداد کل در دیتای دریافتی موجود است یا طول آرایه را می‌گیرید -->
                            <span>{{ comments.total_comments_count }} دیدگاه</span>
                        </div>
                        <ul v-if="comments.data && comments.data.length > 0">
                            <li v-for="(commentItem, index) in comments.data" :key="commentItem.id">
                                <div class="zo-comment">
                                    <v-row dense class="align-center">
                                        <v-col lg="9">
                                            <div class="zo-name">
                                                <div class="zo-avatar">
                                                    <img :src="commentItem.avatar" alt="user avatar">
                                                </div>
                                                <div>
                                                    <strong>{{ commentItem.name }}</strong>
                                                    <small>{{ commentItem.created_at }}</small>
                                                </div>
                                            </div>
                                        </v-col>
                                        <v-col lg="3">
                                            <div class="text-end">
                                                <!-- نکته: در کد اصلی شما اینجا متغیر commentId استفاده شده بود که اشتباه بود، به commentItem.id تغییر کرد -->
                                                <v-btn flat density="compact" variant="text" color="primary"
                                                       icon="mdi-reply" @click="openReplyDialog(commentItem.id)">
                                                </v-btn>
                                            </div>
                                        </v-col>
                                    </v-row>
                                    <p>{{ commentItem.body }}</p>
                                </div>

                                <!-- پاسخ‌ها -->
                                <ul v-if="commentItem.replies && commentItem.replies.length">
                                    <li class="zo-support" v-for="(reply, rIndex) in commentItem.replies" :key="reply.id">
                                        <div class="zo-comment">
                                            <v-row dense class="align-center">
                                                <v-col lg="9">
                                                    <div class="zo-name">
                                                        <div class="zo-avatar">
                                                            <img :src="reply.avatar" alt="">
                                                        </div>
                                                        <div>
                                                            <strong>{{ reply.name }}</strong>
                                                            <small>{{ reply.created_at }}</small>
                                                        </div>
                                                    </div>
                                                </v-col>
                                            </v-row>
                                            <p>{{ reply.body }}</p>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <div v-else class="text-center pa-4 text-grey">
                            هنوز دیدگاهی ثبت نشده است. اولین نفر باشید!
                        </div>
                        <Pagination
                            v-model="currentPage"
                            :length="lastPage"
                            @changePage="fetchComments"
                        />
                    </div>
                </v-col>
            </v-row>
        </div>
    </div>

    <ShowMessage
        v-model:show="message.isShow"
        :message="message.text"
        :type="message.type"
    />
</template>

<script setup>
import {ref, onMounted, computed} from "vue";
import { route } from "ziggy-js";
import axios from "axios"; // مطمئن شوید axios ایمپورت شده است
import ShowMessage from "@/Components/ShowMessage.vue";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    course: {
        type: Object,
        required: true
    },
    user: {
        type: Object,
    },
    // comments prop removed as requested
})

// استیت‌ها
const comments = ref({ data: [], meta: {} });
const lastPage = computed( () => comments.value?.meta.last_page)
const currentPage = ref(comments.value?.meta.current_page)

const loadingComments = ref(true); // وضعیت لودینگ اولیه
const comment = ref({
    name: '',
    email: '',
    body: '',
});
const message = ref({
    isShow: false,
    text: '',
    type: '',
})
const replyDialog = ref(false);
const replyText = ref("");
const activeCommentId = ref(null);
const submitting = ref(false);

// --- متد دریافت کامنت‌ها ---
const fetchComments = async (page = 1) => {
    loadingComments.value = true;

    // اگر ورودی عدد نبود (مثلا ایونت کلیک بود)، همان صفحه جاری بماند
    const targetPage = typeof page === 'number' ? page : currentPage.value;

    try {
        // ارسال پارامتر page به سمت سرور
        const response = await axios.get(route('web.comments.course.index', props.course.slug), {
            params: {
                page: targetPage
            }
        });

        // ذخیره کل دیتا (شامل data, meta, total_comments_count)
        comments.value = response.data.data;

        // --- آپدیت کردن وضعیت صفحه‌بندی ---
        if (comments.value.meta) {
            currentPage.value = comments.value.meta.current_page;
            lastPage.value = comments.value.meta.last_page;
        }

    } catch (error) {
        console.error("Error fetching comments:", error);
        // ... مدیریت خطا
    } finally {
        loadingComments.value = false;
    }
}

// فراخوانی متد دریافت اطلاعات به محض لود شدن کامپوننت
onMounted(() => {
    fetchComments();
});

function openReplyDialog(id) {
    activeCommentId.value = id;
    replyDialog.value = true;
}

async function submitReply() {
    if (!replyText.value.trim()) return;

    console.log("reply to:", activeCommentId.value);
    console.log("text:", replyText.value);

    const response = axios.post(route('web.comments.course.reply', activeCommentId.value), {body: replyText.value})

    replyDialog.value = false;
    replyText.value = "";

}

const submitComment = async () => {
    try {
        submitting.value = true
        const response = await axios.post(route('web.comments.course.store', props.course.slug), {
            name: comment.value.name,
            email: comment.value.email,
            body: comment.value.body,
        });

        submitting.value = false
        if(response.status === 200){
            if(response.data.status === 'sucess' || response.data.status === 'success'){ // هندل کردن تایپوگرافی احتمالی
                comment.value.name = '';
                comment.value.email = '';
                comment.value.body = '';
                message.value.isShow = true;
                message.value.text = response.data.message;
                message.value.type = 'success'

            }
            else if(response.data.status === 'error'){
                message.value.isShow = true;
                message.value.text = response.data.message;
                message.value.type = 'error'
            }
        }
    }
    catch (error) {
        submitting.value = false;
        message.value.isShow = true;
        // هندل کردن ارورهای ولیدیشن
        if (error.response && error.response.data && error.response.data.message) {
            message.value.text = error.response.data.message;
        } else {
            message.value.text = 'خطایی رخ داده است';
        }
        message.value.type = 'error'
    }
}
</script>

<style scoped>
.zo-comments-section .zo-stats {
    width: 100%;
    padding: 0 0 5px;
    border-bottom: 1px solid rgb(240, 240, 240)
}

.zo-comments-section .zo-stats span {
    position: relative;
    font-family: 'Estedad-Medium'
}

.zo-comments-section .zo-stats span:before {
    content: '';
    width: 100%;
    height: 1px;
    position: absolute;
    bottom: -4.5px;
    right: 0;
    background: rgb(5, 105, 60)
}

.zo-comments-section ul {
    list-style: none; /* حذف بولت‌های پیش‌فرض */
    padding: 0;
}

.zo-comments-section ul li {
    width: 100%;
    display: block; /* تغییر از inline-block به block برای اطمینان از چیدمان صحیح */
}

.zo-comments-section ul li .zo-comment {
    width: 100%;
    padding: 15px
}

.zo-comments-section ul li ul {
    padding: 0 15px 0 0
}

.zo-comments-section ul li ul li.zo-support .zo-comment {
    background: rgb(245, 245, 245)
}

.zo-comments-section ul li .zo-comment .zo-name {
    display: flex;
    align-items: center;
    gap: 5px
}

.zo-comments-section ul li .zo-comment .zo-name .zo-avatar img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 50%
}

.zo-comments-section ul li .zo-comment .zo-name strong {
    display: block;
    font-family: 'Estedad-Medium';
    font-weight: normal
}

.zo-comments-section ul li .zo-comment .zo-name small {
    color: rgb(120, 125, 135)
}

.zo-comments-section ul li .zo-comment p {
    white-space: pre-line
}
</style>
