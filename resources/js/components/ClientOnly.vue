<script setup lang="ts">
import { onMounted, ref } from 'vue';

/**
 * Renders its slot only after mount.
 *
 * Unovis builds its charts against a real DOM, so it renders a different tree
 * on the server than on the client. That drifts Vue's `useId` counter and every
 * id generated after it stops matching, which surfaces as hydration mismatches
 * in unrelated components. Keeping those charts out of the server render
 * removes the drift at the source.
 *
 * The wrapper element is always rendered — on both server and client — so the
 * hydration walk sees the same node either way. `display: contents` keeps it
 * out of the layout, so the chart still sizes against the real parent.
 */
const mounted = ref(false);

onMounted(() => {
    mounted.value = true;
});
</script>

<template>
    <div style="display: contents">
        <slot v-if="mounted" />
    </div>
</template>
