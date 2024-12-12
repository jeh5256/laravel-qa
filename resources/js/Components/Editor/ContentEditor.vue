<template>
     <ckeditor 
            :editor="ClassicEditor" 
            v-model="body" 
            :config="ckeditorConfig"
        >
        </ckeditor>
</template>

<script setup>
    import { defineProps, ref } from 'vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    const props = defineProps({
        content: {
            required: false,
            type: String
        }
    });

    const body = ref(props.content);

    const ckeditorConfig = {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'insertTable',
                    '|',
                    '|',
                    'undo',
                    'redo'
                ]
            },
            table: {
                contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
            },
            language: 'en'
        };
</script>