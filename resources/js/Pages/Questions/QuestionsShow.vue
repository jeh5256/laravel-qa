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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8" v-if="can?.addAnswer">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-col">
                        <h3 class="text-xl">Submit An Answer</h3>
                        <form class="mt-3" @submit.prevent="addAnswer"> 
                            <div>
                                <span 
                                    v-if="errors?.body"
                                    class="text-sm text-red-600 font-bold"
                                >
                                    {{ errors.body }}
                                </span>
                                <textarea class="w-full" v-model="answerText"></textarea>
                            </div>
                            <button 
                                type="submit" 
                                class="px-4 py-3 bg-green-600 mt-5 rounded-md text-white font-bold"
                            >
                                Add Answer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-col">
                        <h3 class="text-xl">Answers ({{ totalAnswers }})</h3>
                        <AnswerItem 
                            v-for="answer in answers" 
                            :answer="answer"
                            :key="answer.id" 
                            :canUserMarkAsBestAnswer="can?.markAsBestAnswer"
                        />
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
    import { computed, ref } from 'vue';
    import { Inertia } from '@inertiajs/inertia';

    const props = defineProps({
        'question': {
            required: true,
            type: Object
        },
        'answers': {
            required: true,
            type: Array
        },
        'errors': {
            type: Array
        },
        'can': {
            required: false,
            type: Object
        }
    });

    const answerText = ref('');

    const totalAnswers = computed(() => {
        return props.answers.length;
    });
    
    const addAnswer = () => {
        Inertia.post(`/questions/${props.question.id}/answers`, {
            body: answerText.value
        }, {
            preserveScroll: true,
        });
    };
</script>