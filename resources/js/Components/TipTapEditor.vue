<template>
    <div class="tiptap-container rounded elevation-1">
        <!-- نوار ابزار اصلی -->
        <!-- نوار ابزار اصلی مدرن -->
        <div v-if="editor" class="editor-toolbar bg-surface border-b px-3 py-2">

            <!-- ردیف اول: ابزارهای اصلی -->
            <div class="d-flex flex-wrap align-center gap-1">

                <!-- تاریخچه (Undo/Redo) -->
                <div class="d-flex align-center">
                    <v-btn icon size="x-small" variant="text" @click="editor.chain().focus().undo().run()" :disabled="!editor.can().undo()">
                        <v-icon>mdi-undo</v-icon>
                        <v-tooltip activator="parent" location="top">بازگشت (Undo)</v-tooltip>
                    </v-btn>
                    <v-btn icon size="x-small" variant="text" @click="editor.chain().focus().redo().run()" :disabled="!editor.can().redo()">
                        <v-icon>mdi-redo</v-icon>
                        <v-tooltip activator="parent" location="top">انجام مجدد (Redo)</v-tooltip>
                    </v-btn>
                </div>

                <v-divider vertical class="mx-2 my-1" style="height: 20px"></v-divider>

                <!-- انتخاب‌گر تیتر و سایز فونت (منوی ترکیبی) -->
                <div class="d-flex align-center bg-grey-lighten-4 rounded px-1">
                    <!-- منوی تیتر -->
                    <v-menu location="bottom">
                        <template v-slot:activator="{ props }">
                            <v-btn v-bind="props" variant="text" size="small" class="text-caption font-weight-bold px-2" :color="editor.isActive('heading') ? 'primary' : 'grey-darken-3'">
                                {{ editor.isActive('heading', { level: 1 }) ? 'H1' :
                                editor.isActive('heading', { level: 2 }) ? 'H2' :
                                    editor.isActive('heading', { level: 3 }) ? 'H3' :
                                        editor.isActive('heading') ? 'H' : 'پاراگراف' }}
                                <v-icon end size="x-small">mdi-chevron-down</v-icon>
                                <v-tooltip activator="parent" location="top">تغییر ساختار متن</v-tooltip>
                            </v-btn>
                        </template>
                        <v-list density="compact" nav>
                            <v-list-item @click="editor.chain().focus().setParagraph().run()" :active="editor.isActive('paragraph')">
                                <template v-slot:prepend><v-icon icon="mdi-text" size="small"/></template>
                                <v-list-item-title>پاراگراف عادی</v-list-item-title>
                            </v-list-item>
                            <v-divider class="my-1"></v-divider>
                            <v-list-item v-for="level in [1, 2, 3, 4]" :key="level" @click="editor.chain().focus().toggleHeading({ level: level }).run()" :active="editor.isActive('heading', { level: level })">
                                <template v-slot:prepend><v-icon :icon="`mdi-format-header-${level}`" size="small"/></template>
                                <v-list-item-title>تیتر {{ level }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>

                    <v-divider vertical class="mx-1" style="height: 16px"></v-divider>

                    <!-- منوی سایز فونت -->
                    <v-menu location="bottom">
                        <template v-slot:activator="{ props }">
                            <v-btn v-bind="props" variant="text" size="small" class="text-caption px-2" color="grey-darken-3">
                                <v-icon start size="small">mdi-format-size</v-icon>
                                سایز
                            </v-btn>
                        </template>
                        <v-list density="compact" max-height="200">
                            <v-list-item @click="editor.chain().focus().unsetFontSize().run()">
                                <v-list-item-title class="text-caption">پیش‌فرض</v-list-item-title>
                            </v-list-item>
                            <v-list-item v-for="size in ['12px', '14px', '16px', '18px', '20px', '24px', '30px']" :key="size" @click="editor.chain().focus().setFontSize(size).run()">
                                <v-list-item-title :style="{ fontSize: size }">{{ size }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>

                <v-divider vertical class="mx-2 my-1" style="height: 20px"></v-divider>

                <!-- استایل متن (Bold, Italic, Underline) -->
                <div class="d-flex align-center">
                    <v-btn icon rounded="sm" size="x-small" variant="text" :class="{'bg-grey-lighten-3': editor.isActive('bold')}" :color="editor.isActive('bold') ? 'black' : 'grey-darken-1'" @click="editor.chain().focus().toggleBold().run()">
                        <v-icon>mdi-format-bold</v-icon>
                        <v-tooltip activator="parent" location="top">Bold</v-tooltip>
                    </v-btn>
                    <v-btn icon rounded="sm" size="x-small" variant="text" :class="{'bg-grey-lighten-3': editor.isActive('italic')}" :color="editor.isActive('italic') ? 'black' : 'grey-darken-1'" @click="editor.chain().focus().toggleItalic().run()">
                        <v-icon>mdi-format-italic</v-icon>
                        <v-tooltip activator="parent" location="top">Italic</v-tooltip>
                    </v-btn>
                    <v-btn icon rounded="sm" size="x-small" variant="text" :class="{'bg-grey-lighten-3': editor.isActive('underline')}" :color="editor.isActive('underline') ? 'black' : 'grey-darken-1'" @click="editor.chain().focus().toggleUnderline().run()">
                        <v-icon>mdi-format-underline</v-icon>
                        <v-tooltip activator="parent" location="top">Underline</v-tooltip>
                    </v-btn>
                    <!-- رنگ هایلایت -->
                    <v-btn icon rounded="sm" size="x-small" variant="text" :class="{'bg-yellow-lighten-4': editor.isActive('highlight')}" :color="editor.isActive('highlight') ? 'orange-darken-2' : 'grey-darken-1'" @click="editor.chain().focus().toggleHighlight().run()">
                        <v-icon>mdi-marker</v-icon>
                        <v-tooltip activator="parent" location="top">هایلایت</v-tooltip>
                    </v-btn>
                </div>

                <v-divider vertical class="mx-2 my-1" style="height: 20px"></v-divider>

                <!-- تراز متن -->
                <div class="d-flex align-center">
                    <v-btn icon rounded="sm" size="x-small" variant="text" :class="{'bg-grey-lighten-3': editor.isActive({ textAlign: 'right' })}" :color="editor.isActive({ textAlign: 'right' }) ? 'black' : 'grey-darken-1'" @click="editor.chain().focus().setTextAlign('right').run()">
                        <v-icon>mdi-format-align-right</v-icon>
                        <v-tooltip activator="parent" location="top">راست‌چین</v-tooltip>
                    </v-btn>
                    <v-btn icon rounded="sm" size="x-small" variant="text" :class="{'bg-grey-lighten-3': editor.isActive({ textAlign: 'center' })}" :color="editor.isActive({ textAlign: 'center' }) ? 'black' : 'grey-darken-1'" @click="editor.chain().focus().setTextAlign('center').run()">
                        <v-icon>mdi-format-align-center</v-icon>
                        <v-tooltip activator="parent" location="top">وسط‌چین</v-tooltip>
                    </v-btn>
                    <v-btn icon rounded="sm" size="x-small" variant="text" :class="{'bg-grey-lighten-3': editor.isActive({ textAlign: 'left' })}" :color="editor.isActive({ textAlign: 'left' }) ? 'black' : 'grey-darken-1'" @click="editor.chain().focus().setTextAlign('left').run()">
                        <v-icon>mdi-format-align-left</v-icon>
                        <v-tooltip activator="parent" location="top">چپ‌چین</v-tooltip>
                    </v-btn>
                </div>

                <v-divider vertical class="mx-2 my-1" style="height: 20px"></v-divider>

                <!-- ابزارهای افزودنی (لینک، عکس، جدول) -->
                <div class="d-flex align-center">
                    <v-btn icon rounded="sm" size="x-small" variant="text" :color="editor.isActive('link') ? 'primary' : 'grey-darken-1'" @click="openLinkDialog">
                        <v-icon>mdi-link</v-icon>
                        <v-tooltip activator="parent" location="top">افزودن لینک</v-tooltip>
                    </v-btn>
                    <v-btn v-if="editor.isActive('link')" icon rounded="sm" size="x-small" variant="text" color="error" @click="editor.chain().focus().unsetLink().run()">
                        <v-icon>mdi-link-off</v-icon>
                        <v-tooltip activator="parent" location="top">حذف لینک</v-tooltip>
                    </v-btn>

                    <v-btn icon rounded="sm" size="x-small" variant="text" color="grey-darken-1" @click="openImageDialog">
                        <v-icon>mdi-image-outline</v-icon>
                        <v-tooltip activator="parent" location="top">افزودن تصویر</v-tooltip>
                    </v-btn>

                    <v-btn icon rounded="sm" size="x-small" variant="text" color="grey-darken-1" @click="openTableDialog">
                        <v-icon>mdi-table</v-icon>
                        <v-tooltip activator="parent" location="top">ایجاد جدول</v-tooltip>
                    </v-btn>
                </div>
            </div>

            <!-- ردیف دوم: تنظیمات جدول (داینامیک) -->
            <v-expand-transition>
                <div v-if="editor.isActive('table')" class="mt-2">
                    <div class="d-flex align-center bg-blue-grey-lighten-5 rounded px-2 py-1 gap-1 border">
                        <v-icon color="blue-grey" size="small" class="me-2">mdi-table-cog</v-icon>
                        <span class="text-caption text-blue-grey font-weight-bold me-2">ابزار جدول:</span>

                        <v-divider vertical class="mx-1" style="height: 16px"></v-divider>

                        <!-- ستون‌ها -->
                        <div class="d-flex">
                            <v-btn icon size="x-small" variant="text" color="blue-grey-darken-1" @click="editor.chain().focus().addColumnBefore().run()">
                                <v-icon>mdi-table-column-plus-before</v-icon>
                                <v-tooltip activator="parent" location="top">ستون جدید (راست)</v-tooltip>
                            </v-btn>
                            <v-btn icon size="x-small" variant="text" color="blue-grey-darken-1" @click="editor.chain().focus().addColumnAfter().run()">
                                <v-icon>mdi-table-column-plus-after</v-icon>
                                <v-tooltip activator="parent" location="top">ستون جدید (چپ)</v-tooltip>
                            </v-btn>
                            <v-btn icon size="x-small" variant="text" color="error" @click="editor.chain().focus().deleteColumn().run()">
                                <v-icon>mdi-table-column-remove</v-icon>
                                <v-tooltip activator="parent" location="top">حذف ستون</v-tooltip>
                            </v-btn>
                        </div>

                        <v-divider vertical class="mx-1" style="height: 16px"></v-divider>

                        <!-- ردیف‌ها -->
                        <div class="d-flex">
                            <v-btn icon size="x-small" variant="text" color="blue-grey-darken-1" @click="editor.chain().focus().addRowBefore().run()">
                                <v-icon>mdi-table-row-plus-before</v-icon>
                                <v-tooltip activator="parent" location="top">ردیف جدید (بالا)</v-tooltip>
                            </v-btn>
                            <v-btn icon size="x-small" variant="text" color="blue-grey-darken-1" @click="editor.chain().focus().addRowAfter().run()">
                                <v-icon>mdi-table-row-plus-after</v-icon>
                                <v-tooltip activator="parent" location="top">ردیف جدید (پایین)</v-tooltip>
                            </v-btn>
                            <v-btn icon size="x-small" variant="text" color="error" @click="editor.chain().focus().deleteRow().run()">
                                <v-icon>mdi-table-row-remove</v-icon>
                                <v-tooltip activator="parent" location="top">حذف ردیف</v-tooltip>
                            </v-btn>
                        </div>

                        <v-divider vertical class="mx-1" style="height: 16px"></v-divider>

                        <!-- ادغام -->
                        <div class="d-flex">
                            <v-btn icon size="x-small" variant="text" color="blue-grey-darken-1" @click="editor.chain().focus().mergeCells().run()">
                                <v-icon>mdi-table-merge-cells</v-icon>
                                <v-tooltip activator="parent" location="top">ادغام سلول‌ها</v-tooltip>
                            </v-btn>
                            <v-btn icon size="x-small" variant="text" color="blue-grey-darken-1" @click="editor.chain().focus().splitCell().run()">
                                <v-icon>mdi-table-split-cell</v-icon>
                                <v-tooltip activator="parent" location="top">جدا کردن سلول‌ها</v-tooltip>
                            </v-btn>
                        </div>

                        <v-spacer></v-spacer>

                        <v-btn size="x-small" color="error" variant="text" prepend-icon="mdi-delete-outline" @click="editor.chain().focus().deleteTable().run()">
                            حذف کل جدول
                        </v-btn>
                    </div>
                </div>
            </v-expand-transition>
        </div>

        <editor-content :editor="editor" class="editor-content-area" />

        <!-- دیالوگ‌های لینک، عکس و جدول (بدون تغییر نسبت به کد قبلی شما) -->
        <v-dialog v-model="linkDialog.show" max-width="450" transition="scale-transition">
            <v-card rounded="lg">
                <!-- هدر رنگی -->
                <v-toolbar density="compact" color="blue-grey-darken-3" class="px-2">
                    <v-icon start>mdi-link-variant</v-icon>
                    <v-toolbar-title class="text-body-1 font-weight-bold">مدیریت لینک</v-toolbar-title>
                    <v-btn icon @click="linkDialog.show = false" size="small"><v-icon>mdi-close</v-icon></v-btn>
                </v-toolbar>

                <v-card-text class="pt-4 pb-2">
                    <p class="text-caption text-grey-darken-1 mb-2">آدرس اینترنتی مورد نظر را وارد کنید:</p>

                    <v-text-field
                        v-model="linkDialog.url"
                        label="آدرس (URL)"
                        placeholder="https://example.com"
                        variant="outlined"
                        density="comfortable"
                        color="primary"
                        prepend-inner-icon="mdi-web"
                        dir="ltr"
                        hide-details="auto"
                        class="mb-4"
                    ></v-text-field>

                    <v-card variant="tonal" class="pa-2 border-dashed">
                        <v-switch
                            v-model="linkDialog.target"
                            label="باز شدن در پنجره جدید (Tab)"
                            color="primary"
                            density="compact"
                            hide-details
                            inset
                        >
                            <template v-slot:label>
                                <span class="text-caption font-weight-bold">باز شدن در پنجره جدید</span>
                            </template>
                        </v-switch>
                    </v-card>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions class="bg-grey-lighten-5 pa-3">
                    <v-spacer></v-spacer>
                    <v-btn color="grey-darken-1" variant="text" class="px-4" @click="linkDialog.show = false">انصراف</v-btn>
                    <v-btn color="blue-grey-darken-3" variant="flat" prepend-icon="mdi-check" class="px-4" @click="applyLink">ثبت لینک</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="imageDialog.show" max-width="500" transition="scale-transition">
            <v-card rounded="lg">
                <v-toolbar density="compact" color="blue-grey-darken-3" class="px-2">
                    <v-icon start>mdi-image-plus</v-icon>
                    <v-toolbar-title class="text-body-1 font-weight-bold">افزودن تصویر</v-toolbar-title>
                    <v-btn icon @click="imageDialog.show = false" size="small"><v-icon>mdi-close</v-icon></v-btn>
                </v-toolbar>

                <v-card-text class="pt-4">
                    <v-text-field
                        v-model="imageDialog.url"
                        label="آدرس تصویر (URL)"
                        placeholder="https://..."
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-link"
                        dir="ltr"
                        clearable
                        hint="لینک مستقیم تصویر را وارد کنید"
                        persistent-hint
                    ></v-text-field>

                    <!-- بخش پیش‌نمایش تصویر -->
                    <div class="mt-4 rounded border border-dashed pa-2 bg-grey-lighten-5 d-flex align-center justify-center" style="min-height: 150px; position: relative; overflow: hidden;">
                        <v-img
                            v-if="imageDialog.url"
                            :src="imageDialog.url"
                            max-height="200"
                            contain
                            aspect-ratio="16/9"
                        >
                            <template v-slot:error>
                                <div class="d-flex flex-column align-center text-grey">
                                    <v-icon size="40">mdi-image-broken-variant</v-icon>
                                    <span class="text-caption mt-1">لینک نامعتبر است</span>
                                </div>
                            </template>
                            <template v-slot:placeholder>
                                <div class="d-flex align-center justify-center fill-height">
                                    <v-progress-circular indeterminate color="primary"></v-progress-circular>
                                </div>
                            </template>
                        </v-img>
                        <div v-else class="d-flex flex-column align-center text-grey-lighten-1">
                            <v-icon size="48">mdi-image-outline</v-icon>
                            <span class="text-caption">پیش‌نمایش تصویر اینجا نمایش داده می‌شود</span>
                        </div>
                    </div>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions class="bg-grey-lighten-5 pa-3">
                    <v-spacer></v-spacer>
                    <v-btn color="grey-darken-1" variant="text" @click="imageDialog.show = false">انصراف</v-btn>
                    <v-btn color="blue-grey-darken-3" variant="flat" prepend-icon="mdi-plus" class="px-4" @click="applyImage" :disabled="!imageDialog.url">افزودن تصویر</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="tableDialog.show" max-width="400" transition="scale-transition">
            <v-card rounded="lg">
                <v-toolbar density="compact" color="blue-grey-darken-3" class="px-2">
                    <v-icon start>mdi-table-plus</v-icon>
                    <v-toolbar-title class="text-body-1 font-weight-bold">افزودن جدول</v-toolbar-title>
                    <v-btn icon @click="tableDialog.show = false" size="small"><v-icon>mdi-close</v-icon></v-btn>
                </v-toolbar>

                <v-card-text class="pt-4">
                    <v-row>
                        <v-col cols="6">
                            <v-text-field
                                v-model.number="tableDialog.rows"
                                type="number"
                                min="1"
                                label="تعداد ردیف"
                                variant="outlined"
                                density="compact"
                                prepend-inner-icon="mdi-table-row"
                                color="teal"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                                v-model.number="tableDialog.cols"
                                type="number"
                                min="1"
                                label="تعداد ستون"
                                variant="outlined"
                                density="compact"
                                prepend-inner-icon="mdi-table-column"
                                color="teal"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <!-- یک نمای شماتیک کوچک برای زیبایی -->
                    <!-- بخش پیش‌نمایش شماتیک جدول -->
                    <div class="mt-4">
                        <div class="d-flex justify-space-between align-center mb-1">
                            <span class="text-caption text-grey-darken-1">پیش‌نمایش ساختار:</span>
                            <span class="text-caption font-weight-bold text-teal">
                                {{ tableDialog.cols || 0 }} ستون × {{ tableDialog.rows || 0 }} ردیف
                            </span>
                        </div>

                        <!-- کانتینر گرید -->
                        <div class="d-flex justify-center align-center bg-grey-lighten-4 rounded border border-dashed py-4" style="min-height: 140px;">

                            <!-- اگر مقادیر معتبر باشند -->
                            <div v-if="tableDialog.rows > 0 && tableDialog.cols > 0"
                                 :style="{
                                     display: 'grid',
                                     /* محدود کردن بصری به ۱۰ تا برای جلوگیری از کرش کردن مرورگر در اعداد بالا */
                                     gridTemplateColumns: `repeat(${Math.min(tableDialog.cols, 10)}, 1fr)`,
                                     gridTemplateRows: `repeat(${Math.min(tableDialog.rows, 10)}, 1fr)`,
                                     gap: '2px',
                                     width: '120px',
                                     aspectRatio: `${Math.min(tableDialog.cols, 10)} / ${Math.min(tableDialog.rows, 10)}`
                                 }"
                            >
                                <!-- رندر کردن سلول‌ها -->
                                <div
                                    v-for="n in (Math.min(tableDialog.rows, 10) * Math.min(tableDialog.cols, 10))"
                                    :key="n"
                                    class="bg-teal-lighten-2 rounded-xs"
                                    style="border: 1px solid rgba(0,0,0,0.05);"
                                ></div>
                            </div>

                            <!-- اگر مقادیر صفر یا نامعتبر باشند -->
                            <div v-else class="text-grey-lighten-1 d-flex flex-column align-center">
                                <v-icon size="large" class="mb-1">mdi-table-off</v-icon>
                                <span class="text-caption">ابعاد را وارد کنید</span>
                            </div>
                        </div>

                        <!-- پیام هشدار کوچک برای جداول خیلی بزرگ -->
                        <div v-if="tableDialog.rows > 10 || tableDialog.cols > 10" class="text-center mt-1">
                            <span class="text-[10px] text-grey" style="font-size: 10px;">
                                * نمایش شماتیک به ۱۰×۱۰ محدود شده است
                            </span>
                        </div>
                    </div>

                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions class="pa-3">
                    <v-spacer></v-spacer>
                    <v-btn color="grey" variant="text" @click="tableDialog.show = false">انصراف</v-btn>
                    <v-btn color="blue-grey-darken-3" variant="flat" prepend-icon="mdi-check" class="px-4" @click="applyTable">ساخت جدول</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </div>
</template>

<script setup>
import { reactive, watch, onBeforeUnmount } from 'vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';

// --- ایمپورت‌های استاندارد ---
import StarterKit from '@tiptap/starter-kit';
import TextAlign from '@tiptap/extension-text-align';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import Underline from '@tiptap/extension-underline';
import Highlight from '@tiptap/extension-highlight';
import BubbleMenuExtension from '@tiptap/extension-bubble-menu';
import { Table } from '@tiptap/extension-table';
import { TableRow } from '@tiptap/extension-table-row';
import { TableCell } from '@tiptap/extension-table-cell';
import { TableHeader } from '@tiptap/extension-table-header';

// --- ایمپورت‌های بخش فونت (جدید) ---
import { TextStyle } from '@tiptap/extension-text-style'; // این خط را اضافه کنید
import { Extension } from '@tiptap/core'; // این خط را اضافه کنید

// تعریف اکستنشن سفارشی FontSize
const FontSize = Extension.create({
    name: 'fontSize',
    addOptions() {
        return {
            types: ['textStyle'],
        };
    },
    addGlobalAttributes() {
        return [
            {
                types: this.options.types,
                attributes: {
                    fontSize: {
                        default: null,
                        parseHTML: element => element.style.fontSize.replace(/['"]+/g, ''),
                        renderHTML: attributes => {
                            if (!attributes.fontSize) {
                                return {};
                            }
                            return {
                                style: `font-size: ${attributes.fontSize}`,
                            };
                        },
                    },
                },
            },
        ];
    },
    addCommands() {
        return {
            setFontSize: fontSize => ({ chain }) => {
                return chain()
                    .setMark('textStyle', { fontSize })
                    .run();
            },
            unsetFontSize: () => ({ chain }) => {
                return chain()
                    .setMark('textStyle', { fontSize: null })
                    .removeEmptyTextStyle() // حذف span اضافی اگر استایلی نمانده باشد
                    .run();
            },
        };
    },
});

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

// --- State ---
const linkDialog = reactive({ show: false, url: '', target: false });
const imageDialog = reactive({ show: false, url: '' });
const tableDialog = reactive({ show: false, rows: 3, cols: 3 });

// --- Editor Config ---
const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit.configure({
            heading: { levels: [1, 2, 3, 4, 5, 6] }
        }),
        Underline,
        Highlight,
        TextStyle, // اضافه کردن اکستنشن پایه استایل
        FontSize,  // اضافه کردن اکستنشن سایز فونت ما
        BubbleMenuExtension.configure({ pluginKey: 'bubbleMenuForImage' }),
        Image.configure({ inline: true, allowBase64: true }),
        Link.configure({ openOnClick: false, autolink: true, linkOnPaste: true }),
        TextAlign.configure({
            types: ['heading', 'paragraph', 'image'],
            alignments: ['left', 'center', 'right', 'justify'],
            defaultAlignment: 'right',
        }),
        Table.configure({ resizable: true }),
        TableRow,
        TableHeader,
        TableCell,
    ],
    editorProps: {
        attributes: { class: 'tiptap-input' },
    },
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML());
    },
});

// --- Methods (بدون تغییر) ---
const openLinkDialog = () => {
    if (!editor.value) return;
    const previousUrl = editor.value.getAttributes('link').href;
    const previousTarget = editor.value.getAttributes('link').target;
    linkDialog.url = previousUrl || '';
    linkDialog.target = previousTarget === '_blank';
    linkDialog.show = true;
};

const applyLink = () => {
    if (linkDialog.url === '') {
        editor.value.chain().focus().extendMarkRange('link').unsetLink().run();
    } else {
        editor.value.chain().focus().extendMarkRange('link')
            .setLink({ href: linkDialog.url, target: linkDialog.target ? '_blank' : null })
            .run();
    }
    linkDialog.show = false;
};

const openImageDialog = () => {
    imageDialog.url = '';
    imageDialog.show = true;
};

const applyImage = () => {
    if (imageDialog.url) {
        editor.value.chain().focus().setImage({ src: imageDialog.url }).run();
    }
    imageDialog.show = false;
};

const openTableDialog = () => {
    tableDialog.rows = 3;
    tableDialog.cols = 3;
    tableDialog.show = true;
};

const applyTable = () => {
    if (editor.value) {
        editor.value.chain().focus()
            .insertTable({
                rows: tableDialog.rows,
                cols: tableDialog.cols,
                withHeaderRow: true
            })
            .run();
    }
    tableDialog.show = false;
};

watch(() => props.modelValue, (newValue) => {
    if (editor.value && newValue !== editor.value.getHTML()) {
        editor.value.commands.setContent(newValue, false);
    }
});

onBeforeUnmount(() => {
    if (editor.value) editor.value.destroy();
});
</script>

<style lang="scss">
/* همان استایل‌های قبلی شما */
.tiptap-container {
    border: 1px solid #ddd;
    background: white;
    position: relative;
}

.tiptap-input {
    min-height: 300px;
    max-height: 700px;
    overflow-y: auto;
    padding: 1.5rem;
    outline: none;
    font-family: 'IRANSans', 'Vazir', sans-serif !important;
    direction: rtl;
    text-align: right;
    line-height: 1.8;

    a { color: #1976D2; cursor: pointer; text-decoration: underline; }

    img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 10px 0;
        &.ProseMirror-selectednode { outline: 3px solid #1976D2; }
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin: 0;
        overflow: hidden;
        table-layout: fixed;

        td, th {
            border: 1px solid #ced4da;
            padding: 8px;
            position: relative;
            vertical-align: top;
            box-sizing: border-box;

            &.selectedCell {
                background: rgba(200, 200, 255, 0.4);
            }
        }
        th { background-color: #f8f9fa; font-weight: bold; text-align: center; }
    }
}
</style>
