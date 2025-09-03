<template>
    <div 
        class="mr-4 md:mr-8 font-bold flex items-center justify-center text-center flex-col"
        :class="{
            'text-green-700': props.voteCount >= 0,
            'text-red-700': props.voteCount < 0
        }"
    >
        <font-awesome-icon
            icon="fa-solid fa-arrow-up" 
            class="text-lg mb-3 font-extrabold cursor-pointer" 
            :class="{
                'text-orange-400': !props.userUpvoted,
                'text-orange-600' : props.userUpvoted
            }"
            transform="grow-5"
            @click.prevent="upVote"
        />
        {{ voteCount }}
        <font-awesome-icon
            icon="fa-solid fa-arrow-up" 
            class="text-lg text-blue-400 mt-3 font-extrabold cursor-pointer" 
            :class="{
                'text-blue-400': !props.userDownVoted,
                'text-blue-600' : props.userDownVoted
            }"
            transform="grow-5 rotate-180"
            @click.prevent="downVote"
        />
        <slot></slot>
    </div>
</template>

<script setup>
   import { router, usePage  } from '@inertiajs/vue3';
   import { useToast } from 'vue-toast-notification';
   import { computed } from 'vue';

   const $toast = useToast();

    const props = defineProps({
        voteCount: {
            type: Number,
            required: true,
        },
        model: {
            type: String,
            required: true,
            validator(value) {
                return ['questions', 'answers'].includes(value);
            }
        },
        modelId: {
            type: Number,
            required: true
        },
        userVoted: {
            type: Boolean,
            default() {
                return false;
            }
        }
    });

    const _vote = vote => {
        if (!canVote.value) {
            return $toast.error('You must be logged in to vote');
        };

        router.post(url.value, {
            vote
        }, {
            preserveScroll: true,
            onSuccess: () => {
                const voteText = vote === -1  ? 'downvoted' : 'upvoted';
                $toast.success(`Successfully ${voteText} answer`);
            },
            onError: () => $toast.error('Something went wrong')
        });
    };

    const upVote = () => {
        _vote(1);
    };

    const downVote = () => {
        _vote(-1);
    };

    const canVote = computed(() => {
        return  usePage().props?.auth?.user ? true : false;
    });

    const url = computed(() => {
        return `/${props.model}/${props.modelId}/vote`;
    });

    const userUpvoted = computed(() => {
        return props.userVoted === 'upvoted';
    });

     const userDownVoted = computed(() => {
        return props.userVoted === 'downvoted';
    });
</script>