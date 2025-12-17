<template>
    <!-- دیالوگ پاسخ -->
    <v-dialog v-model="replyDialog" max-width="500">
        <v-card>
            <v-card-title>ثبت پاسخ</v-card-title>
            <v-card-text>
                <v-textarea v-model="replyText" variant="outlined" rows="4" placeholder="پاسخ خود را بنویسید..." />
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text @click="replyDialog = false">انصراف</v-btn>
                <v-btn color="primary" @click="submitReply" :loading="replying" :disabled="replying">ثبت پاسخ</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
        <div class="zo-comments-section" id="comments">
            <strong class="zo-title">نظرات کاربران</strong>
            <!--
            <div class="zo-label" v-if="user">ثبت دیدگاه</div>
            -->
            <!-- فرم ارسال نظر -->
            <v-row dense v-if="user">
                <v-col cols="12">
                    <v-textarea v-model="comment.body" hide-details variant="outlined" density="comfortable" rows="3" placeholder="* سوال یا دیدگاه خود را بنویسید" prepend-inner-icon="mdi-text-long"></v-textarea>
                </v-col>
                <v-col cols="12" class="text-end">
                    <v-btn flat color="secondary" @click="submitComment" :loading="submitting" :disabled="submitting">ارسال نظر</v-btn>
                </v-col>
            </v-row>
            <!-- لیست نظرات -->
            <v-row dense class="mt-4">
                <v-col cols="12">
                    <!-- لودینگ در زمان دریافت کامنت‌ها -->
                    <div v-if="loadingComments" class="text-center pa-5">
                        <v-progress-circular indeterminate color="#c8a064"></v-progress-circular>
                        <div class="mt-2 text-grey">در حال بارگذاری نظرات...</div>
                    </div>
                    <!-- نمایش نظرات -->
                    <div v-else>
                        <div class="zo-stats" v-if="comments.total_comments_count > 0">
                            <!-- فرض بر این است که تعداد کل در دیتای دریافتی موجود است یا طول آرایه را می‌گیرید -->
                            <span>{{ comments.total_comments_count }} نظر</span>
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
                                        <v-col lg="3" v-if="user">
                                            <div class="text-end">
                                                <v-btn flat density="compact" variant="text" color="primary" icon="mdi-reply" @click="openReplyDialog(commentItem.id)">
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
                        <Pagination v-model="currentPage" :length="lastPage" @changePage="fetchComments" />
                    </div>
                </v-col>
            </v-row>
        </div>
    <ShowMessage v-model:show="message.isShow" :message="message.text" :type="message.type" />
</template>
<script setup>
import { ref, onMounted } from "vue";
import { route } from "ziggy-js";
import axios from "axios";
import ShowMessage from "@/Components/ShowMessage.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    item: { type: Object, required: true },
    user: { type: Object },
    type: { type: String, required: true }
});

/* =======================
   State
======================= */
const comments = ref({
    data: [],
    meta: {},
    total_comments_count: 0
});

const currentPage = ref(1);
const lastPage = ref(1);

const loadingComments = ref(true);
const submitting = ref(false);
const replying = ref(false);

const comment = ref({ body: "" });

const replyDialog = ref(false);
const replyText = ref("");
const activeCommentId = ref(null);

const message = ref({
    isShow: false,
    text: "",
    type: ""
});

/* =======================
   Fetch comments
======================= */
const fetchComments = async (page = currentPage.value) => {
    loadingComments.value = true;

    try {
        const response = await axios.get(
            route(`web.comments.${props.type}.index`, props.item.slug),
            { params: { page } }
        );

        const data = response.data.data;

        comments.value.data = data.data;
        comments.value.meta = data.meta;
        comments.value.total_comments_count = data.total_comments_count ?? 0;

        currentPage.value = data.meta.current_page;
        lastPage.value = data.meta.last_page;

    } catch (error) {
        console.error("Fetch comments error:", error);
    } finally {
        loadingComments.value = false;
    }
};

onMounted(fetchComments);

/* =======================
   Submit comment
======================= */
const submitComment = async () => {
    if (!comment.value.body.trim()) return;

    submitting.value = true;

    try {
        const response = await axios.post(
            route(`web.comments.${props.type}.store`, props.item.slug),
            { body: comment.value.body }
        );

        if (response.data.status === "success") {
            comment.value.body = "";
            message.value = {
                isShow: true,
                text: response.data.message,
                type: "success"
            };

            await fetchComments(1);
        } else {
            message.value = {
                isShow: true,
                text: response.data.message || "عملیات ناموفق",
                type: "error"
            };
        }

    } catch (error) {
        message.value = {
            isShow: true,
            text: error.response?.data?.message || "خطا در ثبت دیدگاه",
            type: "error"
        };
    } finally {
        submitting.value = false;
    }
};

/* =======================
   Reply
======================= */
const openReplyDialog = (id) => {
    activeCommentId.value = id;
    replyText.value = "";
    replyDialog.value = true;
};

const submitReply = async () => {
    if (!replyText.value.trim() || !activeCommentId.value) return;

    replying.value = true;

    try {
        const response = await axios.post(
            route("web.comments.reply", activeCommentId.value),
            { body: replyText.value }
        );

        if (response.data.status === "success") {
            message.value = {
                isShow: true,
                text: response.data.message,
                type: "success"
            };

            replyDialog.value = false;
            replyText.value = "";
            activeCommentId.value = null;

            await fetchComments(currentPage.value);
        } else {
            message.value = {
                isShow: true,
                text: response.data.message || "خطا در ثبت پاسخ",
                type: "error"
            };
        }

    } catch (error) {
        message.value = {
            isShow: true,
            text: "خطا در برقراری ارتباط",
            type: "error"
        };
    } finally {
        replying.value = false;
    }
};
</script>

<style scoped>
.zo-comments-section {
    width: 100%;
    display: inline-block;
    padding: 15px
}

.zo-comments-section .zo-title {
    display: block;
    margin: 0 0 5px;
    font-size: 1.125rem;
    color: var(--Secondary)
}

.zo-comments-section .zo-label {
    display: block;
    margin: 0 0 5px;
    padding: 0 10px 5px 0;
    position: relative;
    font-size: 1rem
}

.zo-comments-section .zo-label:before {
    content: '';
    width: 3.5px;
    height: 75%;
    position: absolute;
    top: 0;
    right: 0;
    background: var(--Primary);
    border-radius: 300px
}

.zo-comments-section :deep(.v-text-field .v-field--variant-outlined) {
    background: rgb(245, 245, 245)
}

.zo-comments-section :deep(.v-text-field .v-field--variant-outlined .v-field__outline__start),
.zo-comments-section :deep(.v-text-field .v-field--variant-outlined .v-field__outline__end) {
    border: 0
}

.zo-comments-section :deep(.v-text-field .v-field--variant-outlined .v-field__outline__notch::before),
.zo-comments-section :deep(.v-text-field .v-field--variant-outlined .v-field__outline__notch::after) {
    border: 0
}

.zo-comments-section :deep(.v-textarea .v-field--no-label textarea) {
    resize: none
}

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
    list-style: none;
    padding: 0;
}

.zo-comments-section ul li {
    width: 100%;
    display: block;
}

.zo-comments-section ul li .zo-comment {
    width: 100%;
    margin: 0 0 5px;
    padding: 15px;
    border-radius: .5rem
}

.zo-comments-section ul li ul {
    padding: 0 15px 0 0
}

.zo-comments-section ul li ul li.zo-support .zo-comment {
    position: relative;
    background: rgb(245, 245, 245);
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
    white-space: nowrap
}

.zo-comments-section ul li .zo-comment .zo-name small {
    color: rgb(120, 125, 135)
}

.zo-comments-section ul li .zo-comment p {
    white-space: pre-line;
    text-align: justify
}

@media (max-width: 960px) {

    .zo-comments-section ul li .zo-comment .zo-name .zo-avatar img {
        width: 45px;
        height: 45px
    }
}
</style>