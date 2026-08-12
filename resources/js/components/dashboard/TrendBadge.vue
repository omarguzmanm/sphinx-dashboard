<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge';

type Props = {
    value: number;
    class?: HTMLAttributes['class'];
};

const props = defineProps<Props>();

const isPositive = computed(() => props.value >= 0);
const label = computed(() => `${isPositive.value ? '+' : ''}${props.value}%`);
</script>

<template>
    <Badge
        :variant="isPositive ? 'success' : 'danger'"
        :class="[
            'rounded-md px-1.5 py-0.5 text-[11px] font-semibold',
            props.class,
        ]"
    >
        {{ label }}
    </Badge>
</template>
