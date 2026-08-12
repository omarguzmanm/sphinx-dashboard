<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ $customizer['direction'] ?? 'ltr' }}"
    data-layout="{{ $customizer['layout'] ?? 'vertical' }}"
    data-boxed-layout="{{ $customizer['container'] ?? 'full' }}"
    data-card-style="{{ $customizer['cardStyle'] ?? 'border' }}"
    @class(['dark' => ($appearance ?? 'system') == 'dark'])
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline script to restore the customizer before first paint --}}
        <script>
            (function() {
                try {
                    const stored = localStorage.getItem('customizer');

                    if (!stored) {
                        return;
                    }

                    const settings = JSON.parse(stored);
                    const root = document.documentElement;

                    if (settings.direction) root.setAttribute('dir', settings.direction);
                    if (settings.layout) root.setAttribute('data-layout', settings.layout);
                    if (settings.container) root.setAttribute('data-boxed-layout', settings.container);
                    if (settings.cardStyle) root.setAttribute('data-card-style', settings.cardStyle);
                } catch (e) {
                    // A malformed value must not block rendering...
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        <x-inertia::head>
            <title>{{ config('app.name', 'Laravel') }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
