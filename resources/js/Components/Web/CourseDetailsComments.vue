<template>
    <div class="zo-space">
        <div class="zo-comments-section">
            <strong class="zo-title">نظرات کاربران</strong>
            <div class="zo-label">ثبت دیدگاه</div>
            <v-row dense>
                <v-col cols="12" lg="6">
                    <v-text-field
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
                        hide-details
                        variant="outlined"
                        density="comfortable"
                        type="text"
                        placeholder="* پست الکترونیک"
                        prepend-inner-icon="mdi-email"
                    />
                </v-col>
                <v-col cols="12">
                    <v-textarea
                        hide-details
                        variant="outlined"
                        density="comfortable"
                        rows="3"
                        placeholder="* سوال یا دیدگاه خود را بنویسید"
                        prepend-inner-icon="mdi-text-long"
                    ></v-textarea>
                </v-col>
                <v-col cols="12" class="text-end">
                    <v-btn flat color="secondary">ارسال دیدگاه</v-btn>
                </v-col>
            </v-row>
        </div>
    </div>
</template>
<script setup>

</script>
