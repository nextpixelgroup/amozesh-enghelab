<template>
    <v-autocomplete
        :model-value="modelValue"
        @update:modelValue="updateValue"
        hide-details
        variant="outlined"
        density="comfortable"
        :label="label"
        :items="sortedItems"
        multiple
        clearable
        :chips="false"
        v-bind="$attrs"
        autocomplete="off"
    >
        <template v-slot:selection="{ item, index }">
      <span v-if="modelValue.length > 0 && index === 0" class="text-caption text-grey">
        انتخاب شده‌ها ({{ modelValue.length }})
      </span>
            <v-chip
                v-else-if="modelValue.length <= 0"
                :text="getItemTitle(item)"
                class="mr-1"
            ></v-chip>
        </template>
    </v-autocomplete>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    },
    items: {
        type: Array,
        required: true,
        default: () => []
    },
    label: {
        type: String,
    },
    sortBySelected: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['update:modelValue']);

const updateValue = (value) => {
    if (!Array.isArray(value)) {
        emit('update:modelValue', value);
        return;
    }

    const previous = Array.isArray(props.modelValue) ? props.modelValue : [];
    const removed = previous.filter(v => !value.includes(v));
    const added = value.filter(v => !previous.includes(v));

    // ترتیب نهایی: ابتدا آیتم‌هایی که باقی مانده‌اند به ترتیب قبلی، سپس آیتم‌های تازه اضافه‌شده
    const ordered = [
        ...previous.filter(v => !removed.includes(v)),
        ...added
    ];

    emit('update:modelValue', ordered);
};

const sortedItems = computed(() => {
    if (!props.sortBySelected || !props.modelValue?.length) {
        return [...props.items];
    }

    return [...props.items].sort((a, b) => {
        const idxA = props.modelValue.indexOf(a.value);
        const idxB = props.modelValue.indexOf(b.value);
        const aSelected = idxA !== -1;
        const bSelected = idxB !== -1;

        if (aSelected && bSelected) {
            return idxA - idxB; // ترتیب انتخاب کاربر
        }
        if (aSelected) return -1;
        if (bSelected) return 1;
        return 0;
    });
});

const getItemTitle = (item) => {
    if (typeof item === 'object' && item !== null) {
        return item.title || '';
    }
    return item;
};
</script>
