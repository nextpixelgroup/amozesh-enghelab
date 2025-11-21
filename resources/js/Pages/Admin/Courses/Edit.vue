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
                                :initialUrl="data.poster.url"
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
                <v-expansion-panels multiple class="mb-3" ref="seasonsContainer" v-model="seasonActivePanel">
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
                            {{ `سرفصل ${sIndex + 1}${season.title ? ` - ${season.title}` : ''}` }}

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
                                    <v-expansion-panels
                                        multiple
                                        :ref="el => lessonsContainers[sIndex] = el"
                                        v-model="lessonActivePanels[sIndex]"
                                    >
                                        <v-expansion-panel
                                            v-for="(lesson, lIndex) in season.lessons"
                                            :key="`lesson-${season.id}-${lesson.id}`"
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
                                                {{ `درس ${lIndex + 1}${lesson.title ? ` - ${lesson.title}` : ''}` }}
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
                                                                :min="1"
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
                                                                :initialUrl="lesson?.poster?.url"
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
                                                                v-model="lesson.quiz.is_active"
                                                                hide-details
                                                                label="فعال سازی آزمون؟"
                                                            >
                                                            </v-checkbox>
                                                        </v-col>
                                                        <v-col class="v-col-12" v-if="lesson.quiz.is_active">
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
                                                        <v-col class="v-col-12" v-if="lesson.quiz.is_active">
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
                                                    <v-expansion-panel-text v-if="lesson.quiz.is_active">
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
                                                                    {{ `سوال ${quIndex + 1}${question.text ? ` - ${question.text}` : ''}` }}
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
                                                                                v-model="question.text"
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
                            <v-checkbox v-model="course.quiz.is_active"
                                        hide-details
                                        label="فعال سازی آزمون پایان دوره؟"
                            >
                            </v-checkbox>
                        </v-col>
                        <v-col class="v-col-12" v-if="course.quiz.is_active">
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
                        <v-col class="v-col-12" v-if="course.quiz.is_active">
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
                    <v-expansion-panels multiple class="my-3" v-show="course.quiz.is_active" ref="finalQuizContainers">
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
                                {{ `سوال ${qIndex + 1}${question.text ? ` - ${question.text}` : ''}` }}
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
                                            v-model="question.text"
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
                    <div class="zo-add text-end" v-if="course.quiz.is_active">
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
                <v-card class="pa-3 mb-3 elevation-2  position-sticky top-0">
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
                        :initialUrl="data.thumbnail.url"
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
                        ویرایش دوره
                    </v-btn>
                </v-card>
            </v-col>
        </v-row>
    </AdminLayout>
</template>

<script setup>
import {nextTick, reactive, ref, useTemplateRef, watch, onMounted, computed} from 'vue';
import MultipleSelector from '@/Components/MultipleSelector.vue';
import Editor from '@tinymce/tinymce-vue'
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import SearchableSelect from "@/Components/SearchableSelect.vue";
import {useSortable} from "@vueuse/integrations/useSortable";
import ImageUploader from "@/Components/ImageUploader.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
const {adminPageTitle} = usePageTitle('ویرایش دوره');
const props = defineProps({
    categories: Object,
    teachers: Object,
    status: Object,
    courses: Object,
    video_upload_slug: String,
    course: Object,
})

const isLoading = ref(false);
// Sort categories to show selected items first
const sortedCategories = computed(() => {
    if (!props.categories) return [];
    return [...props.categories].sort((a, b) => {
        const aSelected = course.category?.includes(a.value) ? -1 : 1;
        const bSelected = course.category?.includes(b.value) ? -1 : 1;
        return aSelected - bSelected;
    });
});

const categories = ref(props.categories);
const teachers = ref(props.teachers);
const status = ref(props.status);
const courses = ref(props.courses);
const data = ref(props.course.data);
const video_upload_slug = ref(props.video_upload_slug);
console.log(data.value)
const courseRequirements = courses;
const showVideo = (slug) => {
    if (slug) {
        window.open(`${video_upload_slug.value}${slug}`, '_blank');
    }
}
/*************************Course*************************/
const course = reactive({
    title: data.value.title,
    slug: data.value.slug,
    category: data.value.categories.map(category => category.value),
    teacher: data.value.teacher.id,
    summary: data.value.summary,
    description: data.value.description,
    requirements: data.value.requirements,
    must_complete_quizzes: data.value.must_complete_quizzes ? 1 : 0,
    status: data.value.status.value,
    thumbnail_id: data.value.thumbnail.id,
    intro_url: data.value.intro_url,
    intro_id: data.value.intro.id,
    poster_id: data.value.poster.id,
    seasons: data.value.seasons,
    quiz: data.value.quiz
});
console.log(data.value)
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
function addLesson(sIndex) {
    course.seasons[sIndex].lessons.push({
        id: crypto.randomUUID(),
        title: '',
        description: '',
        duration: null,
        video_url: '',
        is_active: true,
        has_quiz: false,
        poster_id: null,
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
    course.seasons[sIndex].lessons[lIndex].quiz.questions.splice(quIndex, 1);
}

/********************************final quiz********************************/

function addFinalQuizQuestions() {
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

/*********************** Sortable ****************************/
const seasonActivePanel = ref(false);


const lessonsContainers = ref([])

const seasonActive = ref(null)

watch(seasonActivePanel, (newVal) => {
    nextTick(() => {
        newVal.forEach(panelIndex => {
            console.log('season:'+panelIndex);
            const container = lessonsContainers.value[panelIndex];
            if (!container) return;

            seasonActive.value = panelIndex;
            // اگر قبلاً sortable نشد، فعال کن
            if (!container._sortable) {
                useSortable(container, course.seasons[panelIndex].lessons, {
                    animation: 150,
                    handle: '.lesson-drag-handle',
                });
                container._sortable = true; // علامت زده شد که فعال شد
            }
        });
    });
}, { deep: true });


const questionsContainers = ref(
    course.seasons.map(season =>
        Array(season.lessons.length).fill(null)
    )
);

const lessonActivePanels = ref(
    course.seasons.map(() => []) // هر فصل یک آرایه خالی برای ذخیره ایندکس درس‌های فعال
);
watch(lessonActivePanels, (newActiveLessonIndicesPerSeason) => { // نام متغیر را تغییر دادم برای وضوح بیشتر
    console.log('Active lessons panels changed:', newActiveLessonIndicesPerSeason);

    nextTick(() => {
        // برای هر فصل
        newActiveLessonIndicesPerSeason.forEach((activeLessonIndicesForThisSeason, seasonIndex) => {
            // اگر فصل معتبر نیست، از حلقه خارج شو
            if (!course.seasons[seasonIndex]) return;

            // برای هر درس در فصل
            course.seasons[seasonIndex].lessons.forEach((lesson, lessonIndex) => {
                // بررسی می‌کنیم که آیا پنل درس فعلی باز است یا خیر
                const isCurrentLessonPanelActive = activeLessonIndicesForThisSeason.includes(lessonIndex);

                // اگر درس فعال نیست یا قسمت سوالاتش وجود نداره، ادامه نده
                if (!isCurrentLessonPanelActive || !lesson?.quiz?.questions) {
                    return;
                }

                console.log(`فعال‌سازی sortable برای فصل ${seasonIndex + 1} - درس ${lessonIndex + 1}`);

                const containerComponent = questionsContainers.value[seasonIndex]?.[lessonIndex];
                if (!containerComponent) {
                    console.warn(`کانتینر سوالات برای فصل ${seasonIndex + 1} - درس ${lessonIndex + 1} یافت نشد`);
                    return;
                }

                const targetEl = containerComponent.$el || containerComponent;

                // اگر قبلاً sortable نشده، آن را فعال کن
                if (!targetEl._sortable) {
                    useSortable(targetEl, lesson.quiz.questions, {
                        animation: 150,
                        handle: '.question-drag-handle',
                    });
                    targetEl._sortable = true;
                    console.log(`Sortable فعال شد برای سوالات فصل ${seasonIndex + 1} - درس ${lessonIndex + 1}`);
                }
            });
        });
    });
}, { deep: true });


const finalQuizContainers = useTemplateRef('finalQuizContainers')

useSortable(finalQuizContainers, course.quiz.questions, {
    animation: 150,
    handle: '.final-quiz-drag-handle',
})

function submitForm() {
    router.put(route('admin.courses.update',data.value.id), course, {
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
