<template>
    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Questions
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-col">
                        <QuestionItem :question="question" />
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <h3>Answers ({{ totalAnswers }})</h3>
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-col">
                        <AnswerItem v-for="answer in answers" :answer="answer" :key="answer.id" />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import QuestionItem from '../../Components/Questions/QuestionItem.vue';
import AnswerItem from '../../Components/Answers/AnswerItem.vue';
import { computed } from 'vue';

const props = defineProps({
    'question': {
        required: true,
        type: Object
    },
    'answers': {
        required: true,
        type: Array
    }
});

const totalAnswers = computed(() => {
    return props.answers.length;
});
</script>