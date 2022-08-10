<template>
    {{ answer.body }}
</template>

<script setup>
    defineProps({
        'answer' : {
            required: true,
            type: Object
        }
    });
</script>