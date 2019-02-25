<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form class="card-body" v-if="editing" @submit.prevent="update">
                    <div class="card-title">
                        <input type="text" class="form-control form-control-lg" v-model="title">
                    </div>

                    <hr>

                    <div class="media">
                        <div class="media-body">
                            <div class="form-group">
                                <textarea rows="10" v-model="body" class="form-control" required></textarea>
                            </div>
                            <button class="btn btn-primary" :disabled="isInvalid">Update</button>
                            <button class="btn btn-outline-secondary" @click="cancel" type="button">Cancel</button>
                        </div>
                    </div>
                </form>
                <div class="card-body" v-else>
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <h1>{{ title }}</h1>
                            <div class="ml-auto">
                                <a href="/questions" class="btn btn-outline-secondary">
                                    Back to all questions
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="media">
                        
                        <vote :model="question" name="question"></vote>
                        
                        <div class="media-body">
                            <div v-html="bodyHtml"></div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="ml-auto">
                                        <a v-if="authorize('modify', question)" @click.prevent="edit" class="btn btn-sm btn-outline-info">Edit</a>
                                        <button v-if="authorize('deleteQuestion', question)" @click="destroy" class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                <div class="col-4"></div>
                                <div class="col-4">
        
                                    <author-info 
                                        :model="question" 
                                        label="Asked"
                                    ></author-info>
                                </div>
                            </div>
                        </div>
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
        props: ['question'],
        components: {
            AuthorInfo, Vote
        },
        computed: {
            isInvalid() {
                return this.body.length < 10 || this.title.length < 10;
            },
            endpoint() {
                return `/questions/${this.id}`;
            }
        },
        data() {
            return {
                title: this.question.title,
                body: this.question.body,
                bodyHtml: this.question.body_html,
                id: this.question.id,
                beforeEditCache: {}
            }
        },
        methods: {
            delete() {
                axios.delete(this.endpoint)
                .then(({data}) => {
                    this.$toast.success(data.message, { timeout: 2000 });
                })
                .catch((err) => {
                    this.$toast.error(err.response.data.message, "Error", { timeout: 5000 });
                });

                setTimeout(() => {
                    window.location.href="/questions";
                }, 1000);
            },
            payload() {
                return {
                    title: this.title,
                    body: this.body
                };
            },
            restoreFromCache() {
                this.title = this.beforeEditCache.title;
                this.body = this.beforeEditCache.body;
            },
            setEditCache() {
                this.beforeEditCache = {
                    title: this.question.title,
                    body: this.question.body
                };
            },
        },
        mixins: [mixins]
    }
</script>