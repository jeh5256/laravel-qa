<template>
     <ckeditor 
            :editor="ClassicEditor" 
            v-model="body" 
            :config="ckeditorConfig"
        >
        </ckeditor>
</template>

<script setup>
    import { defineProps, ref, watch } from 'vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    const props = defineProps({
        content: {
            required: false,
            type: String
        }
    });
  
    const emit = defineEmits(['editorUpdate']);

    const body = ref(props.content);
 
    watch(body, (newBody) => {
        if (newBody !== '') {
            emit('editorUpdate', newBody);
        }
    });

    watch(() => props.content, (newContent) => {
        if (newContent === '') {
            body.value = newContent;
        }
    });

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

<style>
    .ck-editor__editable {
        min-height: 250px;
    }
</style>