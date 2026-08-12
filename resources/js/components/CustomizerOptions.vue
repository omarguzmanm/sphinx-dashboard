<script setup lang="ts" generic="T extends string">
import type { LucideIcon } from '@lucide/vue';
import { cn } from '@/lib/utils';

type Option = {
    value: T;
    label: string;
    icon: LucideIcon;
};

defineProps<{
    title: string;
    options: Option[];
    modelValue: T;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: T];
}>();
</script>

<template>
    <div class="space-y-3">
        <p class="text-base font-semibold">{{ title }}</p>

        <div class="flex flex-wrap gap-3">
            <button
                v-for="option in options"
                :key="option.value"
                type="button"
                :aria-pressed="modelValue === option.value"
                :class="
                    cn(
                        'flex h-11 min-w-[7rem] flex-1 items-center justify-center gap-2 rounded-md border text-sm font-medium transition-colors',
                        'hover:bg-accent focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none',
                        modelValue === option.value
                            ? 'border-border bg-muted text-foreground'
                            : 'border-border/70 bg-background text-muted-foreground',
                    )
                "
                @click="emit('update:modelValue', option.value)"
            >
                <component :is="option.icon" class="size-4" />
                {{ option.label }}
            </button>
        </div>
    </div>
</template>
