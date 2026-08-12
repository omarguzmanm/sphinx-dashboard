<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Briefcase, CalendarDays } from '@lucide/vue';
import AnalyticsWelcomeCard from '@/components/dashboard/AnalyticsWelcomeCard.vue';
import MonthlyEarningsCard from '@/components/dashboard/MonthlyEarningsCard.vue';
import RecentTransactionsCard from '@/components/dashboard/RecentTransactionsCard.vue';
import RevenueUpdatesCard from '@/components/dashboard/RevenueUpdatesCard.vue';
import SalesFromLocationsCard from '@/components/dashboard/SalesFromLocationsCard.vue';
import SummaryStatCard from '@/components/dashboard/SummaryStatCard.vue';
import WeeklyStatsCard from '@/components/dashboard/WeeklyStatsCard.vue';
import YearlyBackupCard from '@/components/dashboard/YearlyBackupCard.vue';
</script>

<template>
    <Head title="Analytics" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
        <div class="grid gap-6 lg:grid-cols-4">
            <AnalyticsWelcomeCard
                class="lg:col-span-2"
                earnings="$27,850"
                :earnings-trend="18"
                expense="$18,453"
                :expense-trend="-5"
            />

            <SummaryStatCard
                title="Weekly Sales"
                value="$4,587"
                :trend="18"
                :icon="CalendarDays"
            />

            <SummaryStatCard
                title="Purchase Orders"
                value="230"
                :trend="18"
                :icon="Briefcase"
            />
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <RevenueUpdatesCard class="lg:col-span-2" />

            <div class="flex flex-col gap-6">
                <MonthlyEarningsCard />
                <YearlyBackupCard />
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <WeeklyStatsCard />
            <SalesFromLocationsCard />
            <RecentTransactionsCard />
        </div>
    </div>
</template>
