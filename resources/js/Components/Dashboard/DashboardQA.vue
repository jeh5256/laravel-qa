<template>
    <div v-for="item in items" class="flex">
        <span 
            class="flex items-center mr-2 text-center font-bold"
            :class="{
                'text-green-900': item.vote_count >= 0,
                'text-red-900': item.vote_count < 0
            }"
        >
            {{ item.vote_count }} votes
        </span>
        <div class="py-8 px-5 mt-5 bg-slate-100 rounded-lg w-full">
            <h5>{{ item.title }}</h5>
            <div class="mt-5 overflow-hidden" v-html="item.body_html"></div> 
        </div>
    </div>
</template>
<script setup>
    defineProps({
        items: {
            type: Array,
            required: true
        }
    });
</script>