<template>
    <v-container class="pa-4">
        <v-autocomplete
            v-model="search"
            :items="filteredItems"
            :label="label"
            clearable
            hide-no-data
            hide-details
            @update:model-value="onSelect"
            return-object
            item-title="title"
            item-value="value"
            class="mb-4"
            ref="searchInput"
            autocomplete="off"
            @keydown.enter.prevent="clearSearch"
        >
            <template v-slot:item="{ props, item }">
                <v-list-item
                    v-bind="props"
                    :title="item.raw.title"
                    @click="clearSearch"
                />
            </template>
        </v-autocomplete>

        <v-sheet class="mt-3 pa-3" elevation="1" rounded>
            <div ref="chipsContainer" class="chips-container">
                <div
                    v-for="item in modelValue"
                    :key="item.value"
                    class="chip"
                >
                    <v-icon class="drag-handle">mdi-drag-vertical</v-icon>
                    <div class="chip-content">
                        {{ item.title }}
                    </div>
                    <v-btn
                        icon
                        size="x-small"
                        variant="text"
                        @click="removeChip(item)"
                        class="close-btn"
                    >
                        <v-icon size="small">mdi-close</v-icon>
                    </v-btn>
                </div>
                <div v-if="!modelValue.length" class="text-caption text-medium-emphasis text-center py-4">
                    {{ emptyMessage }}
                </div>
            </div>
        </v-sheet>
    </v-container>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'
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
        handle: '.drag-handle',
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
.chips-container {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-height: 100px;
    padding: 8px;
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    direction: rtl;
    user-select: none;
}

.chips-container.dragging {
    cursor: grabbing;
}

.chip {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: grab;
    user-select: none;
    position: relative;
    display: flex;
    flex-direction: row-reverse;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    width: 100%;
    min-height: 48px;
    direction: rtl;
}

.chip:hover {
    background: #f5f5f5;
}

.drag-handle {
    cursor: grab;
    opacity: 0.7;
    margin-right: 8px;
    margin-left: 4px;
    color: #666;
    transition: all 0.2s ease;
    flex-shrink: 0;
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

.chip-content {
    flex-grow: 1;
    margin-right: 8px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
