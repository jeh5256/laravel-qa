<template>
    <div class="py-3 md:p-6 font my-3 md:m-6 flex">
        <Vote 
            :voteCount="question.vote_count" 
            :model="'questions'"
            :modelId="question.id"
            :userVoted="question.userVoted"
        />
        <div class="w-3/4 md:w-full over">
            <div class="border-b border-slate-400 flex justify-around md:justify-between">
                <h3 class="font-bold">
                    <Link :href="`/questions/${props.question.slug}`">
                    {{ question.title }}
                    </Link>
                </h3>
                <font-awesome-icon 
                    :icon="hasUserFavorited" 
                    class="text-2xl text-yellow-500 mb-3 cursor-pointer" 
                    @click.prevent="favoriteQuestion"
                />
            </div>
            <div class="mt-2 text-sm">
                Asked by {{ question.user.name }} at {{ askedAt }}
            </div>
            <div 
                v-html="question.body"
                class="pt-5 bg-gray-200 p-4 mt-5 rounded-md text-ellipsis overflow-hidden"
            >
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import { formatDistance } from 'date-fns';
    import { Inertia } from '@inertiajs/inertia';
    import { Link } from '@inertiajs/inertia-vue3'
    import { useToast } from 'vue-toast-notification';
    import Vote from '../Vote.vue';

    const $toast = useToast();

    const props = defineProps({
        question: {
            type: Object,
            required: true
        }
    });

    const _vote = vote => {
        Inertia.post(`/questions/${props.question.id}/vote`, {
            vote
        }, {
            preserveScroll: true,
            onSuccess: () => {
                const voteText = vote === -1  ? 'downvoted' : 'upvoted';
                $toast.success(`Successfully ${voteText} answer`);
            },
            onError: () => $toast.success('Something went wrong')
        });
    };

    const upVote = () => {
        _vote(1);
    };

    const downVote = () => {
        _vote(-1);
    };

    const favoriteQuestion = () => {
        Inertia.post(`/questions/${props.question.id}/favorites`,{}, {
            preserveScroll: true,
            onSuccess: () => $toast.success(`Successfully (un)favorited questionh`),
            onError: () => $toast.success('Something went wrong')
        });
    };

    const askedAt = computed(() => {
        return formatDistance(new Date(props.question.created_at), new Date(), { addSuffix: true });
    });

    const votesText = computed(() => {
        return props.question.votes_count > 1 ? 'Votes' : 'Vote';
    });

    const hasUserFavorited = computed(() => {
        return props.question.is_favorited ? 'fa-solid fa-star' : 'fa-regular fa-star';
    });
</script>