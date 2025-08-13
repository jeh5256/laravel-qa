<template>
    <ckeditor :editor="ClassicEditor" v-model="body" :config="config" />
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { 
    ClassicEditor, 
    Essentials, 
    Paragraph, 
    Bold, 
    Italic, 
    Heading, 
    List, 
    Table 
} from 'ckeditor5';

import 'ckeditor5/ckeditor5.css';

const props = defineProps({
    content: {
        required: false,
        type: String
    }
});

const emit = defineEmits(['editorUpdate']);

const body = ref(props.content);

watch(body, (newBody) => {
    emit('editorUpdate', newBody);
});

watch(() => props.content, (newContent) => {
    if (newContent === '') {
        body.value = newContent;
    }
});


const config = computed(() => {
    return {
        licenseKey: 'GPL', // Or 'GPL'.
        plugins: [Essentials, Paragraph, Bold, Italic, Heading, List, Table],
        toolbar: [
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
        ],
        table: {
            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
        },
        language: 'en'
    };
});

</script>

<style>
.ck-editor__editable {
    min-height: 250px;
}
</style>