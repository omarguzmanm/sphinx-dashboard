<script setup lang="ts">
import {
    Banknote,
    CreditCard,
    EllipsisVertical,
    Landmark,
    Wallet,
} from '@lucide/vue';
import type { LucideIcon } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardAction,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

type Transaction = {
    title: string;
    description: string;
    amount: string;
    icon: LucideIcon;
    tone: string;
};

const transactions: Transaction[] = [
    {
        title: 'PayPal Transfer',
        description: 'Money added',
        amount: '+$6,235',
        icon: Banknote,
        tone: 'bg-sky-500/12 text-sky-600 dark:text-sky-400',
    },
    {
        title: 'Wallet',
        description: 'Big Brands',
        amount: '+$345',
        icon: Wallet,
        tone: 'bg-teal-500/12 text-teal-600 dark:text-teal-400',
    },
    {
        title: 'Credit card',
        description: 'Money reversed',
        amount: '+$2,235',
        icon: CreditCard,
        tone: 'bg-amber-500/15 text-amber-600 dark:text-amber-400',
    },
    {
        title: 'Bank Transfer',
        description: 'Money withdrawn',
        amount: '-$1,120',
        icon: Landmark,
        tone: 'bg-orange-500/12 text-orange-600 dark:text-orange-400',
    },
    {
        title: 'Refund',
        description: 'Money added',
        amount: '+$580',
        icon: Wallet,
        tone: 'bg-violet-500/12 text-violet-600 dark:text-violet-400',
    },
];
</script>

<template>
    <Card class="gap-5 py-5">
        <CardHeader>
            <CardTitle class="text-lg">Recent Transactions</CardTitle>

            <CardAction>
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button
                            variant="ghost"
                            size="icon-sm"
                            class="rounded-full text-muted-foreground"
                            aria-label="Transaction options"
                        >
                            <EllipsisVertical />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuItem>Export CSV</DropdownMenuItem>
                        <DropdownMenuItem>View all</DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </CardAction>
        </CardHeader>

        <CardContent class="space-y-7">
            <div
                v-for="transaction in transactions"
                :key="transaction.title"
                class="flex items-center gap-3"
            >
                <span
                    class="flex size-10 shrink-0 items-center justify-center rounded-lg"
                    :class="transaction.tone"
                >
                    <component :is="transaction.icon" class="size-5" />
                </span>

                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold">
                        {{ transaction.title }}
                    </p>
                    <p class="truncate text-xs text-muted-foreground">
                        {{ transaction.description }}
                    </p>
                </div>

                <span class="text-sm font-semibold tabular-nums">
                    {{ transaction.amount }}
                </span>
            </div>
        </CardContent>
    </Card>
</template>
