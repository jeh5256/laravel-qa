<template>
    <div v-for="item in items" class="flex">
        <span 
            class="flex items-center mr-2 text-center font-bold"
            :class="{
                'text-green-700': item.vote_count >= 0,
                'text-red-700': item.vote_count < 0
            }"
        >
            {{ item.vote_count }} votes
        </span>
        <div class="py-8 px-5 mt-5 bg-slate-100 rounded-lg w-full">
            <h3 v-if="item.title" class="font-bold">{{ item.title }}</h3>
            <div class="mt-5 overflow-hidden" v-html="item.body"></div> 
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