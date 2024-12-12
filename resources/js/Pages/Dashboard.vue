<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <DashboardItem title="Latest Questions">
                <DashboardQA :items="questions" />
            </DashboardItem>
             <DashboardItem title="Latest Answers">
                 <DashboardQA :items="answers" />
             </DashboardItem>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated';
    import { Head } from '@inertiajs/inertia-vue3';
    import DashboardItem from '@/Components/Dashboard/DashboardItem';
    import DashboardQA from '@/Components/Dashboard/DashboardQA';

    defineProps({
        questions: {
            type: Array
        },
        answers: {
            type: Array
        }
    })
</script>