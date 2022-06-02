<script setup>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
    import { Head } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        You're logged in!
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-10">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="font-bold pb-5">Latest Questions</h3>
                    
                        <div class="py-8 px-5 mt-5 bg-slate-100 rounded-lg" v-for="question in questions">
                            <h5>{{ question.title }}</h5>
                            <div class="mt-5 overflow-hidden" v-html="question.body_html" /> 
                        </div>
                    </div>
                </div>
            </div>
             <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-10">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="font-bold pb-5">Latest Answers</h3>
                    
                        <div class="py-8 px-5 mt-5 bg-slate-100 rounded-lg" v-for="answer in answers">
                            <h5>{{ answer.title }}</h5>
                            <div class="mt-5 overflow-hidden" v-html="answer.body_html" /> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
<script>
    export default {
        props: {
            questions: {
                type: Array
            },
            answers: {
                type: Array
            }
        }
    }
</script>