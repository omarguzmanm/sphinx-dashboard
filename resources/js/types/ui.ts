export type Appearance = 'light' | 'dark' | 'system';
export type ResolvedAppearance = 'light' | 'dark';

export type AppVariant = 'header' | 'sidebar';

export type ThemeDirection = 'ltr' | 'rtl';
export type LayoutType = 'vertical' | 'horizontal';
export type ContainerOption = 'boxed' | 'full';
export type CardStyle = 'border' | 'shadow';

export type CustomizerSettings = {
    direction: ThemeDirection;
    layout: LayoutType;
    container: ContainerOption;
    cardStyle: CardStyle;
    primaryColor: string;
    secondaryColor: string;
};

export type FlashToast = {
    type: 'success' | 'info' | 'warning' | 'error';
    message: string;
};
