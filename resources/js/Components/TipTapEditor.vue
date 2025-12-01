<template>
    <div class="tiptap-container rounded elevation-1">
        <!-- نوار ابزار اصلی -->
        <div v-if="editor" class="editor-toolbar bg-grey-lighten-4 pa-2 border-b">
            <div class="d-flex flex-wrap gap-1 align-center">

                <!-- تاریخچه (Undo/Redo) -->
                <v-btn-group density="compact" variant="text" class="bg-white elevation-1 me-2">
                    <v-btn icon="mdi-undo" size="small" @click="editor.chain().focus().undo().run()" :disabled="!editor.can().undo()" />
                    <v-btn icon="mdi-redo" size="small" @click="editor.chain().focus().redo().run()" :disabled="!editor.can().redo()" />
                </v-btn-group>

                <!-- استایل متن پایه -->
                <v-btn-group density="compact" variant="text" class="bg-white elevation-1 me-2">
                    <v-btn icon="mdi-format-bold" size="small" :color="editor.isActive('bold') ? 'primary' : ''" @click="editor.chain().focus().toggleBold().run()" />
                    <v-btn icon="mdi-format-italic" size="small" :color="editor.isActive('italic') ? 'primary' : ''" @click="editor.chain().focus().toggleItalic().run()" />
                    <v-btn icon="mdi-format-underline" size="small" :color="editor.isActive('underline') ? 'primary' : ''" @click="editor.chain().focus().toggleUnderline().run()" />
                </v-btn-group>

                <!-- منوی انتخاب تیتر (H1-H6) -->
                <v-menu location="bottom">
                    <template v-slot:activator="{ props }">
                        <v-btn v-bind="props" icon size="small" class="bg-white elevation-1 me-2" :color="editor.isActive('heading') ? 'primary' : ''">
                            <v-icon>mdi-format-header-pound</v-icon>
                        </v-btn>
                    </template>
                    <v-list density="compact">
                        <v-list-item v-for="level in [1, 2, 3, 4, 5, 6]" :key="level" @click="editor.chain().focus().toggleHeading({ level: level }).run()" :active="editor.isActive('heading', { level: level })">
                            <v-list-item-title>تیتر {{ level }} (H{{ level }})</v-list-item-title>
                        </v-list-item>
                        <v-divider class="my-1"></v-divider>
                        <v-list-item @click="editor.chain().focus().setParagraph().run()">
                            <v-list-item-title>پاراگراف عادی</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>

                <!-- لینک و عکس -->
                <v-btn-group density="compact" variant="text" class="bg-white elevation-1 me-2">
                    <v-btn icon="mdi-link" size="small" :color="editor.isActive('link') ? 'primary' : ''" @click="openLinkDialog" />
                    <v-btn icon="mdi-link-off" size="small" v-if="editor.isActive('link')" @click="editor.chain().focus().unsetLink().run()" />
                    <v-btn icon="mdi-image" size="small" @click="openImageDialog" />
                </v-btn-group>

                <!-- دکمه ایجاد جدول (با دیالوگ) -->
                <v-btn icon="mdi-table-plus" variant="text" class="bg-white elevation-1 me-2" size="small" @click="openTableDialog" />

                <!-- تراز متن -->
                <v-btn-group density="compact" variant="text" class="bg-white elevation-1">
                    <v-btn icon="mdi-format-align-right" size="small" :color="editor.isActive({ textAlign: 'right' }) ? 'primary' : ''" @click="editor.chain().focus().setTextAlign('right').run()" />
                    <v-btn icon="mdi-format-align-center" size="small" :color="editor.isActive({ textAlign: 'center' }) ? 'primary' : ''" @click="editor.chain().focus().setTextAlign('center').run()" />
                    <v-btn icon="mdi-format-align-left" size="small" :color="editor.isActive({ textAlign: 'left' }) ? 'primary' : ''" @click="editor.chain().focus().setTextAlign('left').run()" />
                </v-btn-group>
            </div>

            <!-- نوار ابزار مخصوص جدول (فقط وقتی داخل جدول هستیم نمایش داده می‌شود) -->
            <div v-if="editor.isActive('table')" class="mt-2 pt-2 border-t d-flex flex-wrap gap-1 bg-grey-lighten-5 rounded pa-1">
                <span class="text-caption align-self-center text-grey ms-2">تنظیمات جدول:</span>

                <!-- ستون -->
                <v-btn-group density="compact" variant="text" class="bg-white elevation-1 mx-1">
                    <v-tooltip activator="parent" location="top">افزودن ستون (قبل)</v-tooltip>
                    <v-btn icon="mdi-table-column-plus-before" size="x-small" @click="editor.chain().focus().addColumnBefore().run()" />
                    <v-btn icon="mdi-table-column-plus-after" size="x-small" @click="editor.chain().focus().addColumnAfter().run()" />
                    <v-btn icon="mdi-table-column-remove" size="x-small" color="error" @click="editor.chain().focus().deleteColumn().run()" />
                </v-btn-group>

                <!-- ردیف -->
                <v-btn-group density="compact" variant="text" class="bg-white elevation-1 mx-1">
                    <v-tooltip activator="parent" location="top">افزودن ردیف (بعد)</v-tooltip>
                    <v-btn icon="mdi-table-row-plus-before" size="x-small" @click="editor.chain().focus().addRowBefore().run()" />
                    <v-btn icon="mdi-table-row-plus-after" size="x-small" @click="editor.chain().focus().addRowAfter().run()" />
                    <v-btn icon="mdi-table-row-remove" size="x-small" color="error" @click="editor.chain().focus().deleteRow().run()" />
                </v-btn-group>

                <!-- سلول -->
                <v-btn-group density="compact" variant="text" class="bg-white elevation-1 mx-1">
                    <v-tooltip activator="parent" location="top">ادغام/جدا کردن سلول</v-tooltip>
                    <v-btn icon="mdi-table-merge-cells" size="x-small" @click="editor.chain().focus().mergeCells().run()" />
                    <v-btn icon="mdi-table-split-cell" size="x-small" @click="editor.chain().focus().splitCell().run()" />
                </v-btn-group>

                <v-btn prepend-icon="mdi-delete" size="x-small" color="error" variant="flat" class="ms-auto" @click="editor.chain().focus().deleteTable().run()">حذف جدول</v-btn>
            </div>
        </div>


        <!-- بدنه اصلی ادیتور -->
        <editor-content :editor="editor" class="editor-content-area" />

        <!-- دیالوگ لینک -->
        <v-dialog v-model="linkDialog.show" max-width="400">
            <v-card title="مدیریت لینک">
                <v-card-text>
                    <v-text-field v-model="linkDialog.url" label="آدرس (URL)" variant="outlined" density="compact" class="mb-2" dir="ltr"></v-text-field>
                    <v-switch v-model="linkDialog.target" label="باز شدن در تب جدید" color="primary" density="compact" hide-details></v-switch>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="grey" variant="text" @click="linkDialog.show = false">انصراف</v-btn>
                    <v-btn color="primary" variant="flat" @click="applyLink">ذخیره</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- دیالوگ عکس -->
        <v-dialog v-model="imageDialog.show" max-width="400">
            <v-card title="افزودن تصویر">
                <v-card-text>
                    <v-text-field v-model="imageDialog.url" label="آدرس تصویر" variant="outlined" density="compact" dir="ltr"></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="grey" variant="text" @click="imageDialog.show = false">انصراف</v-btn>
                    <v-btn color="primary" variant="flat" @click="applyImage">افزودن</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- دیالوگ جدید: تنظیمات جدول -->
        <v-dialog v-model="tableDialog.show" max-width="350">
            <v-card title="ایجاد جدول جدید">
                <v-card-text>
                    <v-row dense>
                        <v-col cols="6">
                            <v-text-field v-model.number="tableDialog.rows" type="number" min="1" label="تعداد ردیف" variant="outlined" density="compact"></v-text-field>
                        </v-col>
                        <v-col cols="6">
                            <v-text-field v-model.number="tableDialog.cols" type="number" min="1" label="تعداد ستون" variant="outlined" density="compact"></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="grey" variant="text" @click="tableDialog.show = false">انصراف</v-btn>
                    <v-btn color="primary" variant="flat" @click="applyTable">ایجاد جدول</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </div>
</template>

<script setup>
import { reactive, watch, onBeforeUnmount } from 'vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';

// --- اکستنشن‌ها ---
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
const tableDialog = reactive({ show: false, rows: 3, cols: 3 }); // استیت جدید برای جدول

// --- Editor Config ---
const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit.configure({
            // Heading در استارتر کیت هست، اینجا فعالش می‌ذاریم
            heading: { levels: [1, 2, 3, 4, 5, 6] }
        }),
        Underline,
        Highlight,
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

// --- Methods ---

// Link
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

// Image
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

// Table Methods
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

    /* تنظیمات جدول */
    table {
        border-collapse: collapse;
        width: 100%;
        margin: 0;
        overflow: hidden;
        table-layout: fixed; /* برای مدیریت بهتر ستون‌ها */

        td, th {
            border: 1px solid #ced4da;
            padding: 8px;
            position: relative;
            vertical-align: top;
            box-sizing: border-box;

            /* نمایش کنترل‌های تغییر سایز فقط وقتی انتخاب شده */
            &.selectedCell {
                background: rgba(200, 200, 255, 0.4);
            }
        }
        th { background-color: #f8f9fa; font-weight: bold; text-align: center; }
    }
}
</style>
