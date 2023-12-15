<template>
    <Head title="Questions" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Question
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-col">
                        <form @submit.prevent="create">
                            <div class="py-4">
                                <label for="title" class="font-bold">Title</label>
                                <input type="text" v-model="form.title" class="w-full" id="title"/>
                            </div>
                            <div class="py-4">
                                <label for="slug" class="font-bold">slug</label>
                                <input type="text" v-model="form.slug" class="w-full" id="slug"/>
                            </div>
                            <div class="py-4">
                                <span 
                                    v-if="errors?.body"
                                    class="text-sm text-red-600 font-bold"
                                >
                                    {{ errors.body }}
                                </span>
                                <label for="body" class="font-bold">Body</label>
                                <ckeditor :editor="ClassicEditor" v-model="form.body" :config="ckeditorConfig" class="py-4 min-h-[300px]" id="body"></ckeditor>
                            </div>
                            <button 
                                type="submit" 
                                class="px-4 py-3 bg-green-600 mt-5 rounded-md text-white font-bold"
                            >
                                Add Question
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
    
</template>

<script setup>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
    import { Head } from '@inertiajs/inertia-vue3';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import { useForm } from '@inertiajs/inertia-vue3';
    import { watch } from 'vue';

    const props = defineProps({
        'errors': {
            type: Object
        }
    });
   

    const form = useForm({
        title: '',
        slug: '',
        body: ''
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


    const create = () => {
        form.post('/questions', {
            titLe: form.title,
            body: form.body
        }, {
            preserveScroll: true,
            onSuccess: page => {console.log(page)},
        });
    };
    
    const slugify = (str) => str.toLowerCase()
                            .replace(/[^a-z0-9]+/g, '-')
                            .replace(/(^-|-$)+/g, '');

    watch(
        () => form.title,
        (title) => {
            form.slug = slugify(title);
        }
    );
</script>

<style>
    .ck-editor__editable {
        min-height: 250px;
    }
</style>