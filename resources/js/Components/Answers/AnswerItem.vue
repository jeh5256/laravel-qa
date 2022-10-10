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
            <div 
                v-html="answer.body"
                class="pt-5 bg-gray-200 p-4 mt-5 rounded-md text-ellipsis overflow-hidden"
            >
            </div>
            <div class="mt-2 text-sm">
                Answered by {{ answer.user.name }} at {{ askedAt }}
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import { formatDistance } from 'date-fns';
    import { Inertia } from '@inertiajs/inertia';
    import { useToast } from 'vue-toast-notification';
    import Vote from '../Vote.vue';

    const $toast = useToast();

    const props = defineProps({
        'answer' : {
            required: true,
            type: Object
        },
        canUserMarkAsBestAnswer: {
            require: false,
            default: false,
            type: Boolean
        }
    });

    const favoriteAnswer = () => {
        Inertia.post(`/answers/${props.answer.id}/accept`,{}, {
            preserveScroll: true,
            onSuccess: () => $toast.success('Answer (un)marked as best answer'),
            onError: () => $toast.success('Something went wrong')
        })
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
</script>