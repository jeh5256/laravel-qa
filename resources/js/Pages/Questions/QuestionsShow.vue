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
                        <form class="mt-3" @submit.prevent="addAnswer" :disabled="form.processing"> 
                            <div>
                                <span 
                                    v-if="errors?.body"
                                    class="text-sm text-red-600 font-bold"
                                >
                                    {{ errors.body }}
                                </span>
                                <content-editor
                                    class="py-4 min-h-[300px]" 
                                    id="body"
                                    :content="form.body" 
                                    @editor-update="(body) => form.body = body" 
                                />
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
                            :questionId="question.id"
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
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated';
    import QuestionItem from '../../Components/Questions/QuestionItem';
    import AnswerItem from '../../Components/Answers/AnswerItem';
    import { computed} from 'vue';
    import ContentEditor from '../../components/Editor/ContentEditor';
    import { useForm } from '@inertiajs/inertia-vue3';
    import { useToast } from 'vue-toast-notification';

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
            type: Object
        },
        'can': {
            required: false,
            type: Object
        }
    });

    const $toast = useToast();

    const form = useForm({
        body: ''
    });
    
    const totalAnswers = computed(() => {
        return props.answers.length;
    });
    
    const addAnswer = () => {
        form.submit('post', `/questions/${props.question.id}/answers`, 
        {
            preserveScroll: true,
            onSuccess: page => { 
                console.log(form);
                form.reset();
                console.log('form body', form.body);
                $toast.success('Answer added') 
            },
            onerror: page => { $toast.error('Error adding answer') },
        });
    };
</script>