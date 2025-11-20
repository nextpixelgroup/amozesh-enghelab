<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <v-row dense class="position-relative">
            <v-col class="v-col-12 v-col-lg-9">
                <v-card class="pa-3 mb-3 elevation-2">
                    <v-row dense class="position-relative">
                        <v-col class="v-col-12">
                            <v-text-field
                                v-model="course.title"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                label="عنوان دوره"
                                type="text"
                                prepend-inner-icon="mdi-text-short"
                            />
                        </v-col>
                        <v-col class="v-col-12">
                            <v-text-field
                                type="text"
                                v-model="course.slug"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                label="نامک"
                                suffix="https://amozesh.enghelab.test/courses/"
                                dir="ltr"
                                prepend-inner-icon="mdi-link"
                            >
                                <template v-slot:append>
                                    <v-btn
                                        icon
                                        variant="text"
                                        :disabled="!course.slug"
                                        @click=""
                                        title="مشاهده"
                                    >
                                        <v-icon>mdi-open-in-new</v-icon>
                                    </v-btn>
                                </template>
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-12">
                            <ImageUploader
                                v-model:model-value="course.poster_id"
                                upload-route="admin.upload.courses.image"
                                title="آپلود تصویر ویدیو مقدمه"
                                label="تصویر ویدیو مقدمه را اینجا رها کنید"
                                accept="image/*"
                                type="lesson"
                            />
                        </v-col>
                        <v-col class="v-col-12">
                            <v-text-field
                                v-model="course.intro_url"
                                type="text"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                label="لینک ویدیو مقدمه"
                                :suffix="video_upload_slug"
                                dir="ltr"
                                prepend-inner-icon="mdi-link"
                            >
                                <template v-slot:append>
                                    <v-btn
                                        icon
                                        variant="text"
                                        @click="showVideo(course.intro_url)"
                                        title="مشاهده"
                                        :disabled="!course.intro_url"
                                    >
                                        <v-icon>mdi-open-in-new</v-icon>
                                    </v-btn>
                                </template>
                            </v-text-field>
                        </v-col>
                        <v-col class="v-col-12 v-col-lg-6">
                            <MultipleSelector
                                v-model="course.category"
                                :items="categories"
                                label="دسته‌بندی‌"
                                prepend-inner-icon="mdi-format-list-group-plus"
                            />
                        </v-col>
                        <v-col class="v-col-12 v-col-lg-6">
                            <v-autocomplete
                                v-model="course.teacher"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                label="مدرس"
                                :items="teachers"
                                clearable
                                prepend-inner-icon="mdi-human-male-board"
                            />
                        </v-col>
                        <v-col class="v-col-12">
                            <v-textarea
                                v-model="course.summary"
                                label="خلاصه"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-text-long"
                            />
                        </v-col>
                        <v-col class="v-col-12">
                            <Editor
                                api-key="kvdbqg230zkimldk8fapggyvjb9gmfa547eveky0zcfgg1zq"
                                v-model="course.description"
                                :init="{
                                    height: 400,
                                    menubar: true,
                                    language: 'fa',
                                    plugins: 'link image media table code lists',
                                    images_upload_url: '/upload/image',
                                    file_picker_types: 'image media',
                                }"
                            />
                        </v-col>
                    </v-row>
                </v-card>
                <div class="zo-header-section mb-5">
                    <v-row class="align-center">
                        <v-col class="v-col-12">
                            <div class="zo-info d-lg-flex d-sm-none">
                                <div class="zo-icon elevation-4">
                                    <i class="mdi mdi-play-box-multiple"></i>
                                </div>
                                <div class="zo-name">
                                    <strong class="d-block mb-1">افزودن پیش نیاز</strong>
                                    <span>در این بخش می توانید پیش نیاز دوره را براساس دوره های پیشین ایجاد کنید.</span>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </div>
                <SearchableSelect
                    v-model="course.requirements"
                    :items="courseRequirements"
                    label="جستجوی پیش نیازها..."
                    empty-message="هیچ پیش‌نیازی انتخاب نشده است."
                />
                <div class="zo-header-section mb-5">
                    <v-row class="align-center">
                        <v-col class="v-col-lg-9">
                            <div class="zo-info d-lg-flex d-sm-none">
                                <div class="zo-icon elevation-4">
                                    <i class="mdi mdi-book-open-page-variant"></i>
                                </div>
                                <div class="zo-name">
                                    <strong class="d-block mb-1">افزودن سرفصل</strong>
                                    <span>در این بخش می توانید سرفصل های دوره + دروس را ایجاد کنید.</span>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </div>
                <v-expansion-panels multiple class="mb-3" ref="seasonsContainer">
                    <v-expansion-panel
                        v-for="(season, sIndex) in course.seasons"
                        :key="season.id"
                    >
                        <div class="zo-actions">
                            <div class="zo-switch">
                                <v-switch v-model="season.is_active" color="success"></v-switch>
                            </div>
                        </div>
                        <div class="zo-close">
                            <v-btn
                                icon="mdi-close"
                                width="25"
                                height="25"
                                color="error"
                                @click="removeSeason(sIndex)"
                            ></v-btn>
                        </div>
                        <v-expansion-panel-title>
                            <v-btn
                                icon="mdi-drag"
                                width="25"
                                height="25"
                                size="small"
                                class="ml-2 season-drag-handle"
                            ></v-btn>
                            {{ season.title || `سرفصل ${sIndex + 1}` }}
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            <v-text-field
                                v-model="season.title"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                label="عنوان سرفصل"
                                type="text"
                                class="mb-3"
                                prepend-inner-icon="mdi-text-short"
                            />
                            <v-textarea
                                v-model="season.description"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                type="text"
                                label="توضیحات سرفصل"
                                class="mb-3"
                                prepend-inner-icon="mdi-text"
                                rows="3"
                            />
                            <v-row dense class="justify-end position-relative mb-3">
                                <v-col class="v-col-12">
                                    <v-expansion-panels multiple :ref="el => lessonsContainers[sIndex] = el">
                                        <v-expansion-panel
                                            v-for="(lesson, lIndex) in season.lessons"
                                            :key="lesson.id"
                                        >
                                            <div class="zo-actions">
                                                <div class="zo-switch">
                                                    <v-switch v-model="lesson.is_active" color="success"></v-switch>
                                                </div>
                                            </div>
                                            <div class="zo-close">
                                                <v-btn icon="mdi-close" width="25" height="25" color="error"
                                                       @click="removeLesson(sIndex, lIndex)"></v-btn>
                                            </div>
                                            <v-expansion-panel-title>
                                                <v-btn
                                                    icon="mdi-drag"
                                                    width="25"
                                                    height="25"
                                                    size="small"
                                                    class="ml-2 lesson-drag-handle"
                                                ></v-btn>
                                                {{ lesson.title || `درس ${lIndex + 1}` }}
                                            </v-expansion-panel-title>
                                            <v-expansion-panel-text>
                                                <v-card flat>
                                                    <v-row dense class="position-relative">
                                                        <v-col class="v-col-lg-9 v-col-12">
                                                            <v-text-field
                                                                v-model="lesson.title"
                                                                hide-details
                                                                variant="outlined"
                                                                density="comfortable"
                                                                label="عنوان درس"
                                                                type="text"
                                                                prepend-inner-icon="mdi-text-short"
                                                            />
                                                        </v-col>
                                                        <v-col class="v-col-lg-3 v-col-12">
                                                            <v-number-input
                                                                v-model="lesson.duration"
                                                                hide-details
                                                                variant="outlined"
                                                                density="comfortable"
                                                                label="زمان ویدیو (دقیقه)"
                                                                type="text"
                                                                prepend-inner-icon="mdi-clock-time-eight-outline"
                                                                min="1"
                                                            />
                                                        </v-col>
                                                        <v-col class="v-col-12">
                                                            <v-textarea
                                                                v-model="lesson.description"
                                                                hide-details
                                                                variant="outlined"
                                                                density="comfortable"
                                                                type="text"
                                                                label="توضیحات درس"
                                                                rows="2"
                                                                prepend-inner-icon="mdi-text"
                                                            />
                                                        </v-col>
                                                        <v-col class="v-col-12">
                                                            <v-text-field
                                                                v-model="lesson.video_url"
                                                                type="text"
                                                                hide-details
                                                                variant="outlined"
                                                                density="comfortable"
                                                                label="لینک ویدیو"
                                                                :suffix="video_upload_slug"
                                                                dir="ltr"
                                                                prepend-inner-icon="mdi-link"
                                                            >
                                                                <template v-slot:append>
                                                                    <v-btn
                                                                        icon
                                                                        variant="text"
                                                                        @click="showVideo(lesson.video_url)"
                                                                        title="مشاهده"
                                                                        :disabled="!lesson.video_url"
                                                                    >
                                                                        <v-icon>mdi-open-in-new</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                            </v-text-field>
                                                        </v-col>
                                                        <v-col class="v-col-12">
                                                            <ImageUploader
                                                                v-model:model-value="lesson.poster_id"
                                                                upload-route="admin.upload.courses.image"
                                                                title="آپلود تصویر ویدیو"
                                                                label="تصویر ویدیو را اینجا رها کنید"
                                                                accept="image/*"
                                                                type="lesson"
                                                            />
                                                        </v-col>
                                                    </v-row>
                                                    <v-row dense class="mb-3">
                                                        <v-col class="v-col-12">
                                                            <v-checkbox
                                                                v-model="lesson.has_quiz"
                                                                hide-details
                                                                label="فعال سازی آزمون؟"
                                                            >
                                                            </v-checkbox>
                                                        </v-col>
                                                        <v-col class="v-col-12" v-if="lesson.has_quiz">
                                                            <v-text-field
                                                                v-model="lesson.quiz.title"
                                                                hide-details
                                                                variant="outlined"
                                                                density="comfortable"
                                                                label="عنوان آزمون"
                                                                type="text"
                                                                prepend-inner-icon="mdi-text-short"
                                                            />
                                                        </v-col>
                                                        <v-col class="v-col-12" v-if="lesson.has_quiz">
                                                            <v-textarea
                                                                v-model="lesson.quiz.description"
                                                                hide-details
                                                                variant="outlined"
                                                                density="comfortable"
                                                                type="text"
                                                                label="توضیحات آزمون"
                                                                rows="2"
                                                                prepend-inner-icon="mdi-text"
                                                            />
                                                        </v-col>
                                                    </v-row>
                                                    <v-expansion-panel-text v-if="lesson.has_quiz">
                                                        <v-expansion-panels
                                                            multiple
                                                            class="mb-3 questions-container"
                                                            :ref="el => {
                                                                if (!questionsContainers[sIndex]) questionsContainers[sIndex] = [];
                                                                questionsContainers[sIndex][lIndex] = el;
                                                            }"
                                                        >
                                                            <v-expansion-panel
                                                                v-for="(question, quIndex) in lesson.quiz.questions"
                                                                :key="question.id"
                                                            >
                                                                <v-expansion-panel-title>
                                                                    <v-btn icon="mdi-drag"
                                                                           width="25"
                                                                           height="25"
                                                                           size="small"
                                                                           class="ml-2 question-drag-handle"
                                                                    >
                                                                    </v-btn>
                                                                    {{ `سوال ${quIndex + 1}` }}
                                                                </v-expansion-panel-title>
                                                                <div class="zo-actions">
                                                                    <div class="zo-switch">
                                                                        <v-switch v-model="question.is_active"
                                                                                  color="success"></v-switch>
                                                                    </div>
                                                                </div>
                                                                <div class="zo-close">
                                                                    <v-btn
                                                                        icon="mdi-close"
                                                                        width="25"
                                                                        height="25"
                                                                        color="error"
                                                                        @click="removeQuestion(sIndex, lIndex, quIndex)"
                                                                    ></v-btn>
                                                                </div>
                                                                <v-expansion-panel-text>
                                                                    <v-row dense>
                                                                        <v-col class="v-col-12">
                                                                            <v-text-field
                                                                                v-model="question.question"
                                                                                hide-details
                                                                                variant="outlined"
                                                                                density="comfortable"
                                                                                label="سوال"
                                                                                type="text"
                                                                                prepend-inner-icon="mdi-text-short"
                                                                            />
                                                                        </v-col>
                                                                        <v-col class="v-col-12 v-col-lg-3">
                                                                            <div class="zo-option">
                                                                                <v-radio
                                                                                    v-model="question.option1.is_correct"
                                                                                    @change="selectCorrectOption(question, 'option1')"
                                                                                />
                                                                                <v-text-field
                                                                                    v-model="question.option1.text"
                                                                                    hide-details
                                                                                    variant="outlined"
                                                                                    density="comfortable"
                                                                                    label="گزینه 1"
                                                                                    type="text"
                                                                                />
                                                                            </div>
                                                                        </v-col>
                                                                        <v-col class="v-col-12 v-col-lg-3">
                                                                            <div class="zo-option">
                                                                                <v-radio
                                                                                    v-model="question.option2.is_correct"
                                                                                    @change="selectCorrectOption(question, 'option2')"
                                                                                />
                                                                                <v-text-field
                                                                                    v-model="question.option2.text"
                                                                                    hide-details
                                                                                    variant="outlined"
                                                                                    density="comfortable"
                                                                                    label="گزینه 2"
                                                                                    type="text"
                                                                                />
                                                                            </div>
                                                                        </v-col>
                                                                        <v-col class="v-col-12 v-col-lg-3">
                                                                            <div class="zo-option">
                                                                                <v-radio
                                                                                    v-model="question.option3.is_correct"
                                                                                    @change="selectCorrectOption(question, 'option3')"
                                                                                />
                                                                                <v-text-field
                                                                                    v-model="question.option3.text"
                                                                                    hide-details
                                                                                    variant="outlined"
                                                                                    density="comfortable"
                                                                                    label="گزینه 3"
                                                                                    type="text"
                                                                                />
                                                                            </div>
                                                                        </v-col>
                                                                        <v-col class="v-col-12 v-col-lg-3">
                                                                            <div class="zo-option">
                                                                                <v-radio
                                                                                    v-model="question.option4.is_correct"
                                                                                    @change="selectCorrectOption(question, 'option4')"
                                                                                />
                                                                                <v-text-field
                                                                                    v-model="question.option4.text"
                                                                                    hide-details
                                                                                    variant="outlined"
                                                                                    density="comfortable"
                                                                                    label="گزینه 4"
                                                                                    type="text"
                                                                                />
                                                                            </div>
                                                                        </v-col>
                                                                    </v-row>
                                                                </v-expansion-panel-text>
                                                            </v-expansion-panel>
                                                        </v-expansion-panels>
                                                        <div class="zo-add text-end">
                                                            <v-btn
                                                                width="25"
                                                                height="25"
                                                                color="primary"
                                                                @click="addQuestion(sIndex, lIndex)"
                                                                icon="mdi mdi-plus"
                                                            />
                                                        </div>
                                                    </v-expansion-panel-text>
                                                </v-card>
                                            </v-expansion-panel-text>
                                        </v-expansion-panel>
                                    </v-expansion-panels>
                                </v-col>
                            </v-row>
                            <div class="text-left">
                                <v-btn
                                    color="primary"
                                    @click="addLesson(sIndex)"
                                >
                                    افزودن درس
                                </v-btn>
                            </div>
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                </v-expansion-panels>
                <div class="text-left">
                    <v-btn color="primary" @click="addSeason">افزودن سرفصل</v-btn>
                </div>
                <div class="zo-header-section mb-5">
                    <v-row class="align-center">
                        <v-col class="v-col-lg-9">
                            <div class="zo-info d-lg-flex d-sm-none">
                                <div class="zo-icon elevation-4">
                                    <i class="mdi mdi-note-text-outline"></i>
                                </div>
                                <div class="zo-name">
                                    <strong class="d-block mb-1">افزودن آزمون پایان دوره</strong>
                                    <span>در این بخش آزمون پایان دوره را ایجاد کنید.</span>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </div>
                <v-card class="pa-3 mb-3 elevation-2">
                    <v-row dense>
                        <v-col class="v-col-12">
                            <v-checkbox v-model="course.quiz.has_quiz"
                                        hide-details
                                        label="فعال سازی آزمون پایان دوره؟"
                            >
                            </v-checkbox>
                        </v-col>
                        <v-col class="v-col-12" v-if="course.quiz.has_quiz">
                            <v-text-field
                                v-model="course.quiz.title"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                label="عنوان آزمون"
                                type="text"
                                prepend-inner-icon="mdi-text-short"
                            />
                        </v-col>
                        <v-col class="v-col-12" v-if="course.quiz.has_quiz">
                            <v-textarea
                                v-model="course.quiz.description"
                                hide-details
                                variant="outlined"
                                density="comfortable"
                                type="text"
                                label="توضیحات آزمون"
                                rows="2"
                                prepend-inner-icon="mdi-text"
                            />
                        </v-col>
                    </v-row>
                    <v-expansion-panels multiple class="my-3" v-show="course.quiz.has_quiz" ref="finalQuizContainers">
                        <v-expansion-panel
                            v-for="(question, qIndex) in course.quiz.questions"
                            :key="question.id">
                            <v-expansion-panel-title>
                                <v-btn
                                    icon="mdi-drag"
                                    width="25"
                                    height="25"
                                    size="small"
                                    class="ml-2 final-quiz-drag-handle"
                                />
                                {{ `سوال ${qIndex + 1}` }}
                            </v-expansion-panel-title>
                            <div class="zo-actions">
                                <div class="zo-switch">
                                    <v-switch v-model="question.is_active"
                                              color="success"></v-switch>
                                </div>
                            </div>
                            <div class="zo-close">
                                <v-btn
                                    icon="mdi-close"
                                    width="25"
                                    height="25"
                                    color="error"
                                    @click="removeFinalQuizQuestions(qIndex)"
                                ></v-btn>
                            </div>
                            <v-expansion-panel-text>
                                <v-row dense>
                                    <v-col class="v-col-12">
                                        <v-text-field
                                            v-model="question.question"
                                            hide-details
                                            variant="outlined"
                                            density="comfortable"
                                            label="سوال"
                                            type="text"
                                            prepend-inner-icon="mdi-text-short"
                                        />
                                    </v-col>
                                    <v-col class="v-col-12 v-col-lg-3">
                                        <div class="zo-option">
                                            <v-radio
                                                v-model="question.option1.is_correct"
                                                @change="selectCorrectOption(question, 'option1')"
                                            />
                                            <v-text-field
                                                v-model="question.option1.text"
                                                hide-details
                                                variant="outlined"
                                                density="comfortable"
                                                label="گزینه 1"
                                                type="text"
                                            />
                                        </div>
                                    </v-col>
                                    <v-col class="v-col-12 v-col-lg-3">
                                        <div class="zo-option">
                                            <v-radio
                                                v-model="question.option2.is_correct"
                                                @change="selectCorrectOption(question, 'option2')"
                                            />
                                            <v-text-field
                                                v-model="question.option2.text"
                                                hide-details
                                                variant="outlined"
                                                density="comfortable"
                                                label="گزینه 2"
                                                type="text"
                                            />
                                        </div>
                                    </v-col>
                                    <v-col class="v-col-12 v-col-lg-3">
                                        <div class="zo-option">
                                            <v-radio
                                                v-model="question.option3.is_correct"
                                                @change="selectCorrectOption(question, 'option3')"
                                            />
                                            <v-text-field
                                                v-model="question.option3.text"
                                                hide-details
                                                variant="outlined"
                                                density="comfortable"
                                                label="گزینه 3"
                                                type="text"
                                            />
                                        </div>
                                    </v-col>
                                    <v-col class="v-col-12 v-col-lg-3">
                                        <div class="zo-option">
                                            <v-radio
                                                v-model="question.option4.is_correct"
                                                @change="selectCorrectOption(question, 'option4')"
                                            />
                                            <v-text-field
                                                v-model="question.option4.text"
                                                hide-details
                                                variant="outlined"
                                                density="comfortable"
                                                label="گزینه 4"
                                                type="text"
                                            />
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-expansion-panel-text>
                        </v-expansion-panel>
                    </v-expansion-panels>
                    <div class="zo-add text-end" v-if="course.quiz.has_quiz">
                        <v-btn icon="mdi-plus"
                               width="25"
                               height="25"
                               color="primary"
                               @click="addFinalQuizQuestions"
                        >
                        </v-btn>
                    </div>
                </v-card>
            </v-col>
            <v-col class="v-col-12 v-col-lg-3">
                <v-card class="pa-3 mb-3 elevation-2  position-sticky top-0"
                        :style="{ top: stickyOffset + 'px !important' }">
                    <v-select
                        v-model="course.must_complete_quizzes"
                        hide-details
                        :items="[
                            { title: 'خیر', value: 0 },
                            { title: 'بله', value: 1 }
                          ]"
                        label="ضروری بودن آزمون ها"
                        outlined
                        class="mb-3"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi mdi-alert"
                    />
                    <v-select
                        v-model="course.status"
                        hide-details
                        :items="status"
                        label="وضعیت"
                        outlined
                        class="mb-3"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi mdi-flag-outline"
                    />
                    <ImageUploader
                        v-model:model-value="course.thumbnail_id"
                        upload-route="admin.upload.courses.image"
                        title="آپلود تصویر شاخص دوره"
                        label="تصویر شاخص دوره را اینجا رها کنید"
                        accept="image/*"
                        type="course"
                    />
                    <v-btn block
                           size="large"
                           color="primary"
                           type="submit"
                           :loading="isLoading"
                           :disabled="isLoading"
                           @click="submitForm"
                           class="mt-3"
                    >
                        ذخیره دوره
                    </v-btn>
                </v-card>
            </v-col>
        </v-row>
    </AdminLayout>
</template>

<script setup>
import {nextTick, reactive, ref, useTemplateRef, watch, onMounted, onUnmounted} from 'vue';
import Editor from '@tinymce/tinymce-vue'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import SearchableSelect from "@/Components/SearchableSelect.vue";
import {useSortable} from "@vueuse/integrations/useSortable";
import ImageUploader from "@/Components/ImageUploader.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
import MultipleSelector from "@/Components/MultipleSelector.vue";

const {adminPageTitle} = usePageTitle('ایجاد دوره');
const props = defineProps({
    categories: Object,
    teachers: Object,
    status: Object,
    courses: Object,
    video_upload_slug: String,
})
const isLoading = ref(false);
const categories = ref(props.categories);
const teachers = ref(props.teachers);
const status = ref(props.status);
const courses = ref(props.courses);
const video_upload_slug = ref(props.video_upload_slug);
const courseRequirements = courses;
const showVideo = (slug) => {
    if (slug) {
        window.open(`${video_upload_slug.value}${slug}`, '_blank');
    }
}
/*************************Course*************************/
const course = reactive({
    title: '',
    slug: '',
    category: null,
    teacher: null,
    summary: '',
    description: '',
    requirements: [],
    must_complete_quizzes: null,
    status: 'pending',
    thumbnail_id: null,
    intro_url: null,
    poster_id: null,
    seasons: [
        {
            id: crypto.randomUUID(),
            title: '',
            description: '',
            is_active: true,
            lessons: [
                {
                    id: crypto.randomUUID(),
                    title: '',
                    description: '',
                    duration: null,
                    video_url: '',
                    poster_id: null,
                    is_active: true,
                    has_quiz: false,
                    quiz: {
                        id: crypto.randomUUID(),
                        title: '',
                        description: '',
                        is_active: true,
                        questions: [
                            {
                                id: crypto.randomUUID(),
                                text: '',
                                is_active: true,
                                option1: {text: '', is_correct: false},
                                option2: {text: '', is_correct: false},
                                option3: {text: '', is_correct: false},
                                option4: {text: '', is_correct: false},
                            }
                        ]
                    }
                }
            ]
        }
    ],
    quiz: {
        id: crypto.randomUUID(),
        has_quiz: false,
        title: '',
        description: '',
        is_active: true,
        questions: [
            {
                id: crypto.randomUUID(),
                text: '',
                is_active: true,
                option1: {text: '', is_correct: false},
                option2: {text: '', is_correct: false},
                option3: {text: '', is_correct: false},
                option4: {text: '', is_correct: false},
            }
        ]
    }
});

/********************************Seasons********************************/


const seasonsContainer = useTemplateRef('seasonsContainer')

useSortable(seasonsContainer, course.seasons, {
    animation: 150,
    handle: '.season-drag-handle',
})

function addSeason() {
    course.seasons.push({
        id: crypto.randomUUID(),
        title: '',
        description: '',
        is_active: true,
        lessons: [{
            id: crypto.randomUUID(),
            title: '',
            description: '',
            duration: null,
            video_url: '',
            poster_id: null,
            is_active: true,
            has_quiz: false,
            quiz: {
                id: crypto.randomUUID(),
                title: '',
                description: '',
                is_active: true,
                questions: [
                    {
                        id: crypto.randomUUID(),
                        text: '',
                        is_active: true,
                        option1: {text: '', is_correct: false},
                        option2: {text: '', is_correct: false},
                        option3: {text: '', is_correct: false},
                        option4: {text: '', is_correct: false},
                    }
                ]
            }
        }]
    });
}

function removeSeason(index) {
    course.seasons.splice(index, 1);
}

/********************************Lessons********************************/
const lessonsContainers = ref([])
watch(
    () => course.seasons.map(s => s.lessons.length),
    () => {
        nextTick(() => {
            lessonsContainers.value.forEach((container, index) => {
                if (!container) return
                useSortable(container, course.seasons[index].lessons, {
                    animation: 150,
                    handle: '.lesson-drag-handle',
                })
            })
        })
    },
    {deep: true}
)

function addLesson(sIndex) {
    course.seasons[sIndex].lessons.push({
        id: crypto.randomUUID(),
        title: '',
        description: '',
        duration: null,
        video_url: '',
        poster_id: null,
        is_active: true,
        has_quiz: false,
        quiz: {
            questions: [
                {
                    id: crypto.randomUUID(),
                    text: '',
                    is_active: true,
                    option1: {text: '', is_correct: false},
                    option2: {text: '', is_correct: false},
                    option3: {text: '', is_correct: false},
                    option4: {text: '', is_correct: false},
                }
            ]
        },
    });
}

function removeLesson(sIndex, lIndex) {
    course.seasons[sIndex].lessons.splice(lIndex, 1);
}

/********************************questions********************************/
const questionsContainers = ref([])
watch(
    // تابعی که وابستگی‌ها رو برمی‌گردونه: طول سوال ‌های هر درس در هر فصل
    () => course.seasons.map(s => s.lessons.map(l => l.quiz?.questions?.length ?? 0)),
    () => {
        nextTick(() => {
            // برای هر فصل
            questionsContainers.value.forEach((seasonArr, sIndex) => {
                if (!seasonArr || !course.seasons[sIndex]) return;
                // برای هر درس در آن فصل
                seasonArr.forEach((containerComponent, lIndex) => {
                    const lesson = course.seasons[sIndex].lessons[lIndex];
                    if (!containerComponent || !lesson || !lesson.quiz || !lesson.quiz.questions) return;
                    // containerComponent ممکن است یک کامپوننت Vuetify باشه؛ useSortable روی خود المان ریشهٔ DOM آن کار می‌کند.
                    // اگر containerComponent.$el وجود داشت از آن استفاده کن؛ در غیر اینصورت خود containerComponent را پاس کن.
                    const targetEl = containerComponent?.$el ?? containerComponent;
                    try {
                        useSortable(targetEl, lesson.quiz.questions, {
                            handle: '.question-drag-handle',
                            animation: 150,
                        });
                    } catch (e) {
                        // اگر قبلاً sortable ست شده بود ممکنه خطا بده — ایمن نادیده می‌گیریم
                        // console.warn('useSortable question init failed', e)
                    }
                })
            })
        })
    },
    {deep: true}
)

function addQuestion(sIndex, lIndex) {
    course.seasons[sIndex].lessons[lIndex].quiz.questions.push({
        id: crypto.randomUUID(),
        text: '',
        is_active: true,
        option1: {text: '', is_correct: false},
        option2: {text: '', is_correct: false},
        option3: {text: '', is_correct: false},
        option4: {text: '', is_correct: false},
    });
}

function selectCorrectOption(question, optionKey) {
    question.option1.is_correct = false;
    question.option2.is_correct = false;
    question.option3.is_correct = false;
    question.option4.is_correct = false;

    question[optionKey].is_correct = true;
}

function removeQuestion(sIndex, lIndex, quIndex) {
    console.log(sIndex, lIndex, quIndex)
    console.log(course.seasons[sIndex].lessons[lIndex].quiz)
    course.seasons[sIndex].lessons[lIndex].quiz.questions.splice(quIndex, 1);
}

/********************************final quiz********************************/
const finalQuizContainers = useTemplateRef('finalQuizContainers')

useSortable(finalQuizContainers, course.quiz.questions, {
    animation: 150,
    handle: '.final-quiz-drag-handle',
})

function addFinalQuizQuestions() {
    console.log(course.quiz.questions)
    course.quiz.questions.push({
        id: crypto.randomUUID(),
        text: '',
        is_active: true,
        option1: {text: '', is_correct: false},
        option2: {text: '', is_correct: false},
        option3: {text: '', is_correct: false},
        option4: {text: '', is_correct: false},
    });
}

function removeFinalQuizQuestions(index) {
    course.quiz.questions.splice(index, 1);
}

function submitForm() {
    router.post(route('admin.courses.store'), course, {
        preserveScroll: true,
        onStart: () => {
            isLoading.value = true
        },
        onSuccess: () => {

        },
        onFinish: () => {
            isLoading.value = false
        }
    })
}

const stickyOffset = ref(0);

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

function handleScroll() {
    stickyOffset.value = window.scrollY > 10 ? 55 : 0;
}
</script>
<style scoped>
.zo-close {
    position: absolute;
    top: -10px;
    right: -10px;
    z-index: 2;
}

.zo-close button {
    font-size: .75rem;
    cursor: pointer;
}

.zo-actions {
    position: absolute;
    top: 12.5px;
    left: 55px;
    z-index: 15
}

.zo-actions .zo-switch .v-input {
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center
}

.zo-option {
    position: relative
}

.zo-option .v-radio {
    position: absolute;
    top: 2.5px;
    left: 5px;
    background: rgb(255, 255, 255);
    z-index: 15
}

.v-card {
    overflow: visible
}

.thumbnail-badge {
    width: 100%;
}

.thumbnail-badge :deep(.v-badge__badge) {
    cursor: pointer;
    top: 0;
    right: -1px !important;
}

.thumbnail-badge :deep(.v-badge__badge .delete-btn) {
    width: 24px;
    height: 24px;
}

.thumbnail-badge :deep(.v-badge__badge .v-btn) {
    width: 100%;
    height: 100%;
}
</style>
