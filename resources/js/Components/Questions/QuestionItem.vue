<template>
    <div class="py-3 md:p-6 font my-3 md:m-6 flex">
        <div 
            class="mr-4 md:mr-8 font-bold flex items-center justify-center text-center"
            :class="{
                'text-green-700': question.vote_count >= 0,
                'text-red-700': question.vote_count < 0
            }"
        >
            {{ question.vote_count }} <br />{{ votesText }}
        </div>
        <div>
            <div class="border-b border-slate-400 flex justify-around md:justify-between">
                <h3 class="font-bold">
                    {{ question.title }}
                </h3>
                <font-awesome-icon :icon="hasUserFavorited" class="text-2xl text-yellow-500 mb-3" />
            </div>
            <div class="mt-2 text-sm">
                Asked by {{ question.user.name }} at {{ askedAt }}
            </div>
            <div 
                v-html="question.body"
                class="pt-5 bg-gray-200 p-4 mt-5 rounded-md"
            >
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import { formatDistance } from 'date-fns'

    const props = defineProps({
        question: {
            type: Object,
            required: true
        }
    });

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