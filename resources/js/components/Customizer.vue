<script setup lang="ts">
import {
    AlignHorizontalJustifyCenter,
    ArrowLeftRight,
    ArrowRightLeft,
    Layers,
    Maximize,
    Moon,
    PanelLeft,
    PanelTop,
    Pencil,
    Settings,
    SquareDashed,
    Sun,
} from '@lucide/vue';
import { computed, ref } from 'vue';
import CustomizerOptions from '@/components/CustomizerOptions.vue';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import { useAppearance } from '@/composables/useAppearance';
import { useCustomizer } from '@/composables/useCustomizer';
import type {
    Appearance,
    CardStyle,
    ContainerOption,
    LayoutType,
    ThemeDirection,
} from '@/types';

const open = ref(false);

const { resolvedAppearance, updateAppearance } = useAppearance();
const {
    settings,
    setCardStyle,
    setContainer,
    setDirection,
    setLayout,
    setPrimaryColor,
    setSecondaryColor,
} = useCustomizer();

const themeOptions = [
    { value: 'light' as Appearance, label: 'Light', icon: Sun },
    { value: 'dark' as Appearance, label: 'Dark', icon: Moon },
];

const directionOptions = [
    { value: 'ltr' as ThemeDirection, label: 'LTR', icon: ArrowRightLeft },
    { value: 'rtl' as ThemeDirection, label: 'RTL', icon: ArrowLeftRight },
];

const layoutOptions = [
    { value: 'vertical' as LayoutType, label: 'Vertical', icon: PanelLeft },
    { value: 'horizontal' as LayoutType, label: 'Horizontal', icon: PanelTop },
];

const containerOptions = [
    {
        value: 'boxed' as ContainerOption,
        label: 'Boxed',
        icon: AlignHorizontalJustifyCenter,
    },
    { value: 'full' as ContainerOption, label: 'Full', icon: Maximize },
];

const cardOptions = [
    { value: 'border' as CardStyle, label: 'Border', icon: SquareDashed },
    { value: 'shadow' as CardStyle, label: 'Shadow', icon: Layers },
];

const colorSwatches = computed(() => [
    {
        id: 'customizer-primary-color',
        label: 'Primary colour',
        value: settings.value.primaryColor,
        apply: setPrimaryColor,
    },
    {
        id: 'customizer-secondary-color',
        label: 'Secondary colour',
        value: settings.value.secondaryColor,
        apply: setSecondaryColor,
    },
]);

const onColorInput = (event: Event, apply: (value: string) => void) => {
    apply((event.target as HTMLInputElement).value);
};
</script>

<template>
    <button
        type="button"
        class="group fixed right-6 bottom-6 z-40 flex size-12 items-center justify-center rounded-full bg-primary text-primary-foreground shadow-lg transition-shadow hover:shadow-xl focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
        aria-label="Open customizer"
        data-test="customizer-trigger"
        @click="open = true"
    >
        <Settings
            class="size-6 motion-safe:animate-[spin_4s_linear_infinite] group-hover:motion-safe:animate-[spin_1s_linear_infinite]"
        />
    </button>

    <Sheet v-model:open="open">
        <SheetContent
            side="right"
            class="w-full gap-0 overflow-y-auto sm:max-w-sm"
        >
            <SheetHeader class="gap-1 px-6 pt-6">
                <SheetTitle class="text-xl">Customizer</SheetTitle>
                <SheetDescription>
                    Make changes to your customizer here.
                </SheetDescription>
            </SheetHeader>

            <div class="space-y-7 px-6 pt-6 pb-10">
                <CustomizerOptions
                    title="Theme Option"
                    :options="themeOptions"
                    :model-value="resolvedAppearance"
                    @update:model-value="updateAppearance"
                />

                <CustomizerOptions
                    title="Theme Direction"
                    :options="directionOptions"
                    :model-value="settings.direction"
                    @update:model-value="setDirection"
                />

                <div class="space-y-3">
                    <p class="text-base font-semibold">
                        Choose Your Theme Colors
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <label
                            v-for="swatch in colorSwatches"
                            :key="swatch.id"
                            :for="swatch.id"
                            class="flex h-14 min-w-[7rem] flex-1 cursor-pointer items-center justify-center gap-3 rounded-md border border-border/70 transition-colors focus-within:ring-2 focus-within:ring-ring hover:bg-accent"
                        >
                            <span
                                class="size-6 rounded-full border border-border/60"
                                :style="{ backgroundColor: swatch.value }"
                            />
                            <Pencil class="size-4 text-muted-foreground" />
                            <input
                                :id="swatch.id"
                                type="color"
                                class="sr-only"
                                :value="swatch.value"
                                :aria-label="swatch.label"
                                @input="onColorInput($event, swatch.apply)"
                            />
                        </label>
                    </div>
                </div>

                <CustomizerOptions
                    title="Layout Type"
                    :options="layoutOptions"
                    :model-value="settings.layout"
                    @update:model-value="setLayout"
                />

                <CustomizerOptions
                    title="Container Option"
                    :options="containerOptions"
                    :model-value="settings.container"
                    @update:model-value="setContainer"
                />

                <CustomizerOptions
                    title="Card With"
                    :options="cardOptions"
                    :model-value="settings.cardStyle"
                    @update:model-value="setCardStyle"
                />
            </div>
        </SheetContent>
    </Sheet>
</template>
