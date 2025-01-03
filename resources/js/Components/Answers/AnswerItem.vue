<template>
    <div class="py-3 md:p-6 font my-3 md:m-6 flex border-b-2 border-gray-300 last:border-b-0">
       <Vote
            :voteCount="answer.vote_count" 
            :model="'answers'"
            :modelId="answer.id"
            :userVoted="answer.userVoted"
       >
            <font-awesome-icon 
                icon="fa-solid fa-check" 
                transform="grow-5"
                class="text-2xl mb-3 cursor-pointer mt-5"
                :class="{
                    'text-green-500': answer.is_best_answer,
                    'text-gray-600': !answer.is_best_answer
                }"
                v-if="canUserMarkAsBestAnswer"
                @click.prevent="favoriteAnswer"
            />
       </Vote>
        <div class="w-3/4 md:w-full over">
            <div v-show="canEditAnswer">
                <span @click="editingAnswer" class="text-sm">(Edit)</span>
            </div>
            <div 
                v-if="!isEditingAnswer"
                v-html="answerText"
                class="pt-5 bg-gray-200 p-4 mt-2 rounded-md text-ellipsis overflow-hidden"
            >
            </div>
           <div v-else>
                <content-editor :content="answerText" />
                <button 
                    type="submit" 
                    class="px-2 py-1 bg-green-600 mt-3 rounded-md text-white font-bold"
                    @click="updateAnswer"
                >
                    Save Answer
                </button>
           </div>
            <div class="mt-2 text-sm">
                Answered by {{ answer.user.name }} at {{ askedAt }}
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed, ref } from 'vue';
    import { formatDistance } from 'date-fns';
    import { router, usePage } from '@inertiajs/vue3';
    import { useToast } from 'vue-toast-notification';
    import Vote from '@/Components/Vote.vue';
    import ContentEditor from '@/Components/Editor/ContentEditor.vue';

    const $toast = useToast();

    const props = defineProps({
        'answer' : {
            required: true,
            type: Object
        },
        'questionId': {
            required: true,
            type: Number
        },
        canUserMarkAsBestAnswer: {
            require: false,
            default: false,
            type: Boolean
        }
    });

    const isEditingAnswer = ref(false);
    const answerText = ref(props.answer.body);
    const user = computed(() => usePage().props.auth.user);

    const favoriteAnswer = () => {
        router.post(`/answers/${props.answer.id}/accept`,{}, {
            preserveScroll: true,
            onSuccess: () => $toast.success('Answer (un)marked as best answer'),
            onError: () => $toast.success('Something went wrong')
        });
    };

    const editingAnswer = () => {
        isEditingAnswer.value = !isEditingAnswer.value;
    }

    const updateAnswer = () => {
        Inertia.patch(`/questions/${props.questionId}/answers/${props.answer.id}`, {
            body: answerText.value
        }, 
        {
            preserveScroll: true,
            onSuccess: () => $toast.success('Answer has been updated'),
            onError: () => $toast.success('Something went wrong'),
            onFinish: () => isEditingAnswer.value = false
        });
    };

    const askedAt = computed(() => {
        return formatDistance(new Date(props.answer.created_at), new Date(), { addSuffix: true });
    });

    const userUpvoted = computed(() => {
        return props.answer.user_voted === 'upvoted';
    });

     const userDownVoted = computed(() => {
        return props.answer.user_voted === 'downvoted'
    });

    const votesText = computed(() => {
        return props.answer.votes_count > 1 ? 'Votes' : 'Vote';
    });

    const canEditAnswer = computed(() => {
        return user?.id === props.answer.user_id;
    });
</script>