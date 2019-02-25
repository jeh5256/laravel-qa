<template>
    <div class="media post">
                            
        <vote :model="answer" name="answer"></vote>
    
        <div class="media-body">
            <form v-if="editing" @submit.prevent="update">
                <div class="form-group">

                    <textarea class="form-control" rows="10" v-model="body" required></textarea>
                </div>
                <button class="btn btn-primary" :disabled="isInvalid">Update</button>
                <button @click="cancel" type="button" class="btn btn-outline-secondary">Cancel</button>
            </form>
            <div v-else>
                <div v-html="bodyHtml"></div>
                <div class="row">
                    <div class="col-4">
                        <div class="ml-auto">
                            <a v-if="authorize('modify', answer)" @click.prevent="edit" class="btn btn-sm btn-outline-info">Edit</a>
                            <button v-if="authorize('modify', answer)" @click="destroy" class="btn btn-sm btn-outline-danger">
                                Delete
                            </button>
                        </div>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-4">
                        <author-info :model="answer" label="Answered"></author-info>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AuthorInfo from '../components/AuthorInfo';
    import mixins from '../mixins/mixins.js';
    import Vote from '../components/Vote';

    export default {
        props: ['answer'],
        data() {
            return {
                body: this.answer.body,
                bodyHtml: this.answer.body_html,
                id: this.answer.id,
                questionId: this.answer.question_id,
                beforeEditCache: null
            }
        },
        computed: {
            isInvalid() {
                return this.body.length < 10;
            },
            endpoint() {
                return `/questions/${this.questionId}/answers/${this.id}`;
            }
        },
        components: {
            AuthorInfo, Vote
        },
        methods: {
            delete() {
                axios.delete(this.endpoint)
                .then(({data}) => {
                    this.$emit('deleted');
                   this.$toast.success(data.message, "Success", { timeout: 2000 });
                })
                .catch(({data}) => {
                    this.$toast.error(data.message, "Error", { timeout: 3000 });
                }); 
            },
            payload() {
                return { body: this.body }
            },
            restoreFromCache() {
                this.body = this.beforeEditCache;
            },
            setEditCache() {
                this.beforeEditCache = this.body;
            },
        },
        mixins: [mixins]
    }
</script>