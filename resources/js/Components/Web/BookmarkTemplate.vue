<template>
    <v-tooltip text="افزودن به علاقه مندی" v-if="!isBookmarked">
        <template v-slot:activator="{ props }">
            <v-btn
                v-bind="props"
                icon="mdi-bookmark-outline"
                color="primary"
                size="large"
                @click="addBookmark"
                :loading="isLoading"
                :disabled="isLoading"
            ></v-btn>
        </template>
    </v-tooltip>
    <v-tooltip text="حذف از علاقه مندی" v-else>
        <template v-slot:activator="{ props }">
            <v-btn
                v-bind="props"
                icon="mdi-bookmark"
                color="primary"
                size="large"
                @click="removeBookmark"
                :loading="isLoading"
                :disabled="isLoading"
            ></v-btn>
        </template>
    </v-tooltip>
</template>
<script setup>
import {ref} from "vue";
import {route} from "ziggy-js";

const isLoading = ref(false)
const addBookmarkRoute = ref(null)
const removeBookmarkRoute = ref(null)
const props = defineProps({
    id: {
        type: Number,
        required: true,
    },
    isBookmarked: {
        type: Boolean,
        default: false,
        required: true,
    },
    type: {
        type: String,
        required: true,
    }
})
const isBookmarked = ref(props.isBookmarked)
if (props.type === 'course'){
    addBookmarkRoute.value = route('web.bookmark.course.store', { course: props.id })
    removeBookmarkRoute.value = route('web.bookmark.course.destroy', { course: props.id })
}
else if(props.type === 'book'){
    addBookmarkRoute.value = route('web.bookmark.book.store', { course: props.id })
    removeBookmarkRoute.value = route('web.bookmark.book.destroy', { course: props.id })
}

const addBookmark = async () => {
    try {
        isLoading.value = true;
        const response = await axios.post(addBookmarkRoute.value, []);
        if (response.data.status === 'success') {
            isLoading.value = false;
            isBookmarked.value = true;
        } else {
            isLoading.value = false;
        }
    }
    catch (error) {

    }
}

const removeBookmark = async () => {
    try {
        isLoading.value = true;
        const response = await axios.delete(removeBookmarkRoute.value, []);
        if (response.data.status === 'success') {
            isLoading.value = false;
            isBookmarked.value = false;
        } else {
            isLoading.value = false;
        }
    }
    catch (error) {

    }
}
</script>
