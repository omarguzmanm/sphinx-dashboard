import type { Ref } from 'vue';
import { readonly, ref } from 'vue';
import type {
    CardStyle,
    ContainerOption,
    CustomizerSettings,
    LayoutType,
    ThemeDirection,
} from '@/types';

export const CUSTOMIZER_STORAGE_KEY = 'customizer';

export const defaultCustomizer: CustomizerSettings = {
    direction: 'ltr',
    layout: 'vertical',
    container: 'full',
    cardStyle: 'border',
    primaryColor: '#171717',
    secondaryColor: '#ebebeb',
};

export type UseCustomizerReturn = {
    settings: Readonly<Ref<CustomizerSettings>>;
    setDirection: (value: ThemeDirection) => void;
    setLayout: (value: LayoutType) => void;
    setContainer: (value: ContainerOption) => void;
    setCardStyle: (value: CardStyle) => void;
    setPrimaryColor: (value: string) => void;
    setSecondaryColor: (value: string) => void;
    reset: () => void;
};

const settings = ref<CustomizerSettings>({ ...defaultCustomizer });

function readStoredSettings(): CustomizerSettings {
    if (typeof window === 'undefined') {
        return { ...defaultCustomizer };
    }

    try {
        const stored = localStorage.getItem(CUSTOMIZER_STORAGE_KEY);

        return stored
            ? { ...defaultCustomizer, ...JSON.parse(stored) }
            : { ...defaultCustomizer };
    } catch {
        return { ...defaultCustomizer };
    }
}

const setCookie = (value: CustomizerSettings, days = 365) => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;
    const encoded = encodeURIComponent(JSON.stringify(value));

    document.cookie = `${CUSTOMIZER_STORAGE_KEY}=${encoded};path=/;max-age=${maxAge};SameSite=Lax`;
};

/**
 * Pick a readable foreground for an arbitrary background, using the WCAG
 * relative luminance of the colour.
 *
 * @param hex string A `#rrggbb` colour.
 */
function contrastColor(hex: string): string {
    const value = hex.replace('#', '');

    if (value.length !== 6) {
        return '#fafafa';
    }

    const channels = [0, 2, 4].map((offset) => {
        const channel = parseInt(value.slice(offset, offset + 2), 16) / 255;

        return channel <= 0.03928
            ? channel / 12.92
            : ((channel + 0.055) / 1.055) ** 2.4;
    });

    const luminance =
        0.2126 * channels[0] + 0.7152 * channels[1] + 0.0722 * channels[2];

    return luminance > 0.5 ? '#0a0a0a' : '#fafafa';
}

/**
 * Mirror the settings onto `<html>` so plain CSS can react to them.
 */
export function applyCustomizer(value: CustomizerSettings): void {
    if (typeof document === 'undefined') {
        return;
    }

    const root = document.documentElement;

    root.setAttribute('dir', value.direction);
    root.setAttribute('data-layout', value.layout);
    root.setAttribute('data-boxed-layout', value.container);
    root.setAttribute('data-card-style', value.cardStyle);

    // Inline custom properties beat both `:root` and `.dark`, so a chosen
    // colour survives a theme switch.
    root.style.setProperty('--primary', value.primaryColor);
    root.style.setProperty(
        '--primary-foreground',
        contrastColor(value.primaryColor),
    );
    root.style.setProperty('--sidebar-primary', value.primaryColor);
    root.style.setProperty(
        '--sidebar-primary-foreground',
        contrastColor(value.primaryColor),
    );
    root.style.setProperty('--ring', value.primaryColor);
    root.style.setProperty('--secondary', value.secondaryColor);
    root.style.setProperty(
        '--secondary-foreground',
        contrastColor(value.secondaryColor),
    );
}

function persist(value: CustomizerSettings): void {
    if (typeof window === 'undefined') {
        return;
    }

    localStorage.setItem(CUSTOMIZER_STORAGE_KEY, JSON.stringify(value));

    // The cookie is what lets the server render the same layout the client is
    // about to hydrate...
    setCookie(value);
}

function update(patch: Partial<CustomizerSettings>): void {
    settings.value = { ...settings.value, ...patch };

    applyCustomizer(settings.value);
    persist(settings.value);
}

/**
 * Seed the state from the server-shared preferences so the SSR render and the
 * client hydration agree on the layout. `initializeCustomizer` then reconciles
 * with localStorage once the app is running.
 */
export function hydrateCustomizer(shared?: Partial<CustomizerSettings>): void {
    settings.value = { ...defaultCustomizer, ...shared };
}

export function initializeCustomizer(): void {
    settings.value = readStoredSettings();

    applyCustomizer(settings.value);
    setCookie(settings.value);
}

export function useCustomizer(): UseCustomizerReturn {
    return {
        settings: readonly(settings) as Readonly<Ref<CustomizerSettings>>,
        setDirection: (value) => update({ direction: value }),
        setLayout: (value) => update({ layout: value }),
        setContainer: (value) => update({ container: value }),
        setCardStyle: (value) => update({ cardStyle: value }),
        setPrimaryColor: (value) => update({ primaryColor: value }),
        setSecondaryColor: (value) => update({ secondaryColor: value }),
        reset: () => update({ ...defaultCustomizer }),
    };
}
