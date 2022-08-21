<template>
    <div class="py-3 md:p-6 font my-3 md:m-6 flex border-b-2 border-gray-300 last:border-b-0">
        <div 
            class="mr-4 md:mr-8 font-bold flex items-center justify-center text-center flex-col"
            :class="{
                'text-green-700': answer.vote_count >= 0,
                'text-red-700': answer.vote_count < 0
            }"
        >
            <font-awesome-icon
                icon="fa-solid fa-arrow-up" 
                class="text-2xl mb-3 font-extrabold cursor-pointer" 
                :class="{
                    'text-orange-400': !userUpvoted,
                    'text-orange-600' : userUpvoted
                }"
                transform="grow-5"
                @click.prevent="upVote"
            />
            {{ answer.vote_count }}
            <font-awesome-icon
                icon="fa-solid fa-arrow-up" 
                class="text-2xl text-blue-400 mt-3 font-extrabold cursor-pointer" 
                :class="{
                    'text-blue-400': !userDownVoted,
                    'text-blue-600' : userDownVoted
                }"
                transform="grow-5 rotate-180"
                @click.prevent="downVote"
            />
            <font-awesome-icon 
                icon="fa-solid fa-check" 
                transform="grow-5"
                class="text-2xl mb-3 cursor-pointer mt-5"
                :class="{
                    'text-green-500': answer.is_best_answer,
                    'text-gray-600': !answer.is_best_answer
                }"
                @click.prevent="favoriteAnswer"
            />
        </div>
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

    const props = defineProps({
        'answer' : {
            required: true,
            type: Object
        }
    });

    const _vote = vote => {
        Inertia.post(`/answers/${props.answer.id}/vote`, {
            vote
        });
    };

    const upVote = () => {
        _vote(1);
    };

    const downVote = () => {
        _vote(-1);
    };

    const favoriteanswer = () => {
        Inertia.post(`/answers/${props.answer.id}/favorites`);
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