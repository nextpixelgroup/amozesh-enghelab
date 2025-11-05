<template>
    <v-card class="pa-3 mb-3 elevation-2">
        <v-autocomplete
            v-model="search"
            hide-details
            hide-no-data
            clearable
            variant="outlined"
            :items="filteredItems"
            :label="label"
            @update:model-value="onSelect"
            return-object
            item-title="title"
            item-value="value"
            ref="searchInput"
            autocomplete="off"
            @keydown.enter.prevent="clearSearch"
            prepend-inner-icon="mdi-play-box-multiple"
        >
            <template v-slot:item="{ props, item }">
                <v-list-item v-bind="props" :title="item.raw.title" @click="clearSearch"/>
            </template>
        </v-autocomplete>
        <div ref="chipsContainer" class="zo-chips-section">
            <div v-for="item in modelValue" :key="item.value" class="zo-chip">
                <v-icon class="zo-drag">mdi-drag-vertical</v-icon>
                <div class="zo-content">
                    {{ item.title }}
                </div>
                <v-btn icon size="x-small" color="error" @click="removeChip(item)">
                    <v-icon size="small">mdi-close</v-icon>
                </v-btn>
            </div>
        </div>
    </v-card>
</template>

<script setup>
import {ref, computed, onMounted, onBeforeUnmount, nextTick} from 'vue'
import Sortable from 'sortablejs'

// Props
const props = defineProps({
    /** لیست کل آیتم‌ها برای جستجو */
    items: {
        type: Array,
        required: true,
    },
    /** مقدار انتخاب‌شده‌ها (v-model) */
    modelValue: {
        type: Array,
        default: () => [],
    },
    /** برچسب فیلد جستجو */
    label: {
        type: String,
        default: 'جستجو...',
    },
    /** پیام وقتی آیتمی انتخاب نشده */
    emptyMessage: {
        type: String,
        default: 'آیتمی برای نمایش وجود ندارد. از باکس بالایی برای افزودن آیتم جدید استفاده کنید.',
    },
})

// Emits
const emit = defineEmits(['update:modelValue'])

const search = ref(null)
const chipsContainer = ref(null)
const searchInput = ref(null)
let sortable = null

// Computed
const filteredItems = computed(() => {
    const term = search.value?.title?.toLowerCase?.() || search.value?.toLowerCase?.() || ''
    const selectedIds = new Set(props.modelValue.map(i => i.value))
    return props.items.filter(i =>
        i.title.toLowerCase().includes(term) && !selectedIds.has(i.value)
    )
})

// Methods
const clearSearch = () => {
    search.value = null
    nextTick(() => {
        if (searchInput.value) searchInput.value.blur()
    })
}

const onSelect = (item) => {
    if (!item) return
    const already = props.modelValue.some(i => i.value === item.value)
    if (!already) emit('update:modelValue', [...props.modelValue, item])
    clearSearch()
}

const removeChip = (item) => {
    const updated = props.modelValue.filter(i => i.value !== item.value)
    emit('update:modelValue', updated)
}

// Sortable initialization
const initSortable = () => {
    if (!chipsContainer.value || sortable) return

    sortable = new Sortable(chipsContainer.value, {
        animation: 150,
        handle: '.zo-drag',
        ghostClass: 'ghost',
        direction: 'vertical',
        invertSwap: true,
        fallbackTolerance: 5,
        delayOnTouchOnly: true,
        delay: 100,
        onStart: () => {
            document.body.style.cursor = 'grabbing'
            chipsContainer.value.classList.add('dragging')
        },
        onEnd: (e) => {
            document.body.style.cursor = ''
            chipsContainer.value.classList.remove('dragging')

            if (e.oldIndex !== e.newIndex) {
                const items = [...props.modelValue]
                const [moved] = items.splice(e.oldIndex, 1)
                items.splice(e.newIndex, 0, moved)
                emit('update:modelValue', items)
            }
        }
    })
}

// Lifecycle
onMounted(initSortable)
onBeforeUnmount(() => sortable?.destroy())
</script>

<style scoped>
.zo-chips-section {
    display: flex;
    flex-direction: column;
    width: 100%;
    margin: 0 auto
}

.zo-chips-section.dragging {
    cursor: grabbing;
}

.zo-chips-section .zo-chip {
    width: 100%;
    min-height: 50px;
    display: flex;
    flex-direction: row-reverse;
    align-items: center;
    margin: 2.5px 0;
    padding: 10px 15px;
    position: relative;
    bacground: rgb(245, 245, 245);
    border: 1px solid rgb(225, 225, 225);
    border-radius: .25rem;
}

.zo-chips-section .zo-chip:hover {
    background: rgb(225, 225, 225)
}

.zo-chips-section .zo-chip .zo-content {
    flex-grow: 1;
    margin: 0 10px 0 0;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis
}

.zo-chips-section .zo-chip .zo-drag {
    flex-shrink: 0;
    margin: 0 10px 0 0;
    opacity: 0.75;
    cursor: grab
}

.drag-handle:hover {
    color: #1976d2;
    transform: scale(1.1);
}

.ghost {
    opacity: 0.8;
    background: #e3f2fd;
    border: 2px dashed #2196f3;
    box-shadow: 0 2px 6px rgba(33, 150, 243, 0.2);
}
</style>
