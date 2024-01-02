<template>
    <Head title="Questions" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editing: <span class="font-extrabold text-2xl ml-4">{{ question.title }}</span>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-col">
                        <form @submit.prevent="updateQuestion">
                            <div class="py-4 flex flex-col">
                                <label for="title" class="font-bold">Title</label>
                                <input type="text" v-model="form.title" class="w-full" id="title" />
                                <span 
                                    v-if="errors?.title"
                                    class="text-sm text-red-600 font-bold"
                                >
                                    {{ errors.title }}
                                </span>
                            </div>
                            <div class="py-4 flex flex-col">
                                <label for="slug" class="font-bold">slug</label>
                                <input type="text" v-model="form.slug" class="w-full" id="slug" />
                                <span 
                                    v-if="errors?.slug"
                                    class="text-sm text-red-600 font-bold"
                                >
                                    {{ errors.slug }}
                                </span>
                            </div>
                            <div class="py-4 flex flex-col">
                                <label for="body" class="font-bold">Body</label>
                                <ckeditor :editor="ClassicEditor" v-model="form.body" :config="ckeditorConfig" class="py-4 min-h-[300px]" id="body"></ckeditor>
                                <span 
                                    v-if="errors?.body"
                                    class="text-sm text-red-600 font-bold"
                                >
                                    {{ errors.body }}
                                </span>
                            </div>
                            <div class="space-x-4">
                                <button 
                                    type="submit" 
                                    class="px-4 py-3 bg-green-600 mt-5 rounded-md text-white font-bold"
                                >
                                    Update Question
                                </button>
                                <button 
                                    @click="deleteQuestion"
                                    type="button" 
                                    class="px-4 py-3 bg-red-600 mt-5 rounded-md text-white font-bold"
                                >
                                    Delete Question
                                </button>
                            </div>
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
    import { useToast } from 'vue-toast-notification';

    const $toast = useToast();

    const props = defineProps({
        'question': {
            type: Object,
            required: true
        },
        'errors': {
            type: Object
        }
    });
   

    const form = useForm({
        title: props.question.title,
        slug: props.question.slug,
        body: props.question.body
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


    const updateQuestion = () => {
        form.patch(`/questions/${props.question.slug}`, {
            titLe: form.title,
            body: form.body
        }, {
            preserveScroll: true,
            onSuccess: page => { $toast.success('Question updated') },
            onerror: page => { $toast.error('Error updating question') }
        });
    };
    
    const deleteQuestion = () => {
        form.delete(`/questions/${props.question.slug}`, {
            preserveScroll: true,
            onSuccess: page => { $toast.success('Question deleted') },
            onerror: page => { $toast.error('Error deleting question') }
        });
    }

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