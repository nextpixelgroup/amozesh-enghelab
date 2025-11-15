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
    type: [Array, String, Number],
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
  emit('update:modelValue', value);
};

const sortedItems = computed(() => {
  if (!props.sortBySelected || !props.modelValue?.length) {
    return [...props.items];
  }

  return [...props.items].sort((a, b) => {
    const aSelected = props.modelValue.includes(a.value) ? -1 : 1;
    const bSelected = props.modelValue.includes(b.value) ? -1 : 1;
    return aSelected - bSelected;
  });
});

const getItemTitle = (item) => {
  if (typeof item === 'object' && item !== null) {
    return item.title || '';
  }
  return item;
};
</script>
