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
            class="text-2xl mb-3 font-extrabold cursor-pointer" 
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
            class="text-2xl text-blue-400 mt-3 font-extrabold cursor-pointer" 
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
   import { Inertia } from '@inertiajs/inertia';
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
        Inertia.post(url.value, {
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
    
    const url = computed(() => {
        return `/${props.model}/${props.modelId}/vote`;
    });

    const userUpvoted = computed(() => {
        return props.userVoted === 'upvoted';
    });

     const userDownVoted = computed(() => {
        return props.userVoted === 'downvoted'
    });
</script>