<template>
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

                <v-btn text @click="replyDialog = false">
                    انصراف
                </v-btn>

                <v-btn color="primary" @click="submitReply">
                    ثبت پاسخ
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <div class="zo-space" id="comments">
        <div class="zo-comments-section">
            <strong class="zo-title">نظرات کاربران</strong>
            <div class="zo-label">ثبت دیدگاه</div>
            <v-row dense>
                <v-col cols="12" lg="6">
                    <v-text-field
                        v-if="!user"
                        v-model="comment.name"
                        hide-details
                        variant="outlined"
                        density="comfortable"
                        type="text"
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
                        type="text"
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
            <v-row dense>
                <v-col cols="12">
                    <div class="zo-stats">
                        <span>۳ دیدگاه</span>
                    </div>
                    <ul>
                        <li v-for="(comment,index) in comments.data">
                            <div class="zo-comment" :key="comment.id">
                                <v-row dense class="align-center">
                                    <v-col lg="9">
                                        <div class="zo-name">
                                            <div class="zo-avatar">
                                                <img :src="comment.avatar" alt="">
                                            </div>
                                            <div>
                                                <strong>{{comment.name}}</strong>
                                                <small>{{comment.created_at}}</small>
                                            </div>
                                        </div>
                                    </v-col>
                                    <v-col lg="3">
                                        <div class="text-end">
                                            <v-btn flat density="compact" variant="text" color="primary"
                                                   icon="mdi-reply" @click="openReplyDialog(commentId)">
                                            </v-btn>
                                        </div>
                                    </v-col>
                                </v-row>
                                <p>{{comment.body}}</p>
                            </div>
                            <ul v-if="comment.replies.length">
                                <li class="zo-support" v-for="(reply,index) in comment.replies">
                                    <div class="zo-comment">
                                        <v-row dense class="align-center">
                                            <v-col lg="9">
                                                <div class="zo-name">
                                                    <div class="zo-avatar">
                                                        <img :src="reply.avatar" alt="">
                                                    </div>
                                                    <div>
                                                        <strong>{{reply.name}}</strong>
                                                        <small>{{reply.created_at}}</small>
                                                    </div>
                                                </div>
                                            </v-col>
                                        </v-row>
                                        <p>{{reply.body}}</p>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
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
import {ref} from "vue";
import {route} from "ziggy-js";
import ShowMessage from "@/Components/ShowMessage.vue";
const props = defineProps({
    course: {
        type: Object,
    },
    user: {
        type: Object,
    },
    comments: {
        type: Object
    }
})
console.log(props.comments.data)
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
const replying = ref(false);

function openReplyDialog(id) {
    activeCommentId.value = id;
    replyDialog.value = true;
}

function submitReply() {
    if (!replyText.value.trim()) return;

    console.log("reply to:", activeCommentId.value);
    console.log("text:", replyText.value);


    replyDialog.value = false;
    replyText.value = "";
}

const submitComment = async () => {
    try {
        submitting.value = true
        const response = await axios.post(route('web.comments.course.store',props.course.slug), {
            name: comment.value.name,
            email: comment.value.email,
            body: comment.value.body,
        });

        submitting.value = false
        if(response.status == 200){
            if(response.data.status == 'sucess'){
                comment.value.name = '';
                comment.value.email = '';
                comment.value.body = '';
                message.value.isShow = true;
                message.value.text = response.data.message;
                message.value.type = 'success'
            }
            else if(response.data.status == 'error'){
                message.value.isShow = true;
                message.value.text = response.data.message;
                message.value.type = 'error'
            }
        }

    }
    catch (error) {
        message.value.isShow = true;
        message.value.text = error;
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

.zo-comments-section ul li {
    width: 100%;
    display: inline-block
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
