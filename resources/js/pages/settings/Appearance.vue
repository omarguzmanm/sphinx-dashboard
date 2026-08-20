<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { edit } from '@/routes/appearance';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Appearance settings',
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Appearance settings" />

    <h1 class="sr-only">Appearance settings</h1>

    <Card class="max-w-xl">
        <CardHeader>
            <CardTitle class="text-lg">Appearance</CardTitle>
            <CardDescription>
                Update the appearance settings for your account
            </CardDescription>
        </CardHeader>

        <CardContent>
            <AppearanceTabs />
        </CardContent>
    </Card>
</template>
