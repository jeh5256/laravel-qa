<template>
    <div class="p-6 font m-6">
        <h3 class="font-bold border-b border-slate-400">
            {{ question.title }}
        </h3>
        <div class="mt-2 text-sm">
            Asked by {{ question.user.name }} at {{ askedAt }}
        </div>
        <div 
            v-html="question.body"
            class="pt-5 bg-gray-200 p-4 mt-5 rounded-md"
        >
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import formatDistanceToNow from 'date-fns/format';

    const props = defineProps({
        question: {
            type: Object,
            required: true
        }
    });

    const askedAt = computed(() => {
        return formatDistanceToNow(new Date(props.question.user.created_at), { addSuffix: true });
    });
</script>