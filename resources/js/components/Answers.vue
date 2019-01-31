<template>
    <div class="row mt-4" v-cloak v-if="count">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h2>{{ title }}</h2>
                    </div>
                    <hr />

                    <answer v-for="answer in answers" :answer="answer" :key="answer.id"></answer>
                    <div class="text-center mt-3">
                        <button 
                            class="btn btn-outline-secondary" 
                            v-if="nextURL"
                            @click.prevent="fetch(nextURL)"
                        >
                            Load More
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Answer from './Answer';

export default {
    props: ['question'],
    created() {
        this.fetch(`/questions/${this.questionId}/answers`);
    },
    components: {
        Answer
    },
    computed: {
        title() {
            return this.count + ' ' + (this.count > 1) ? 'Answers' : 'Answer';
        }
    },
    data() {
        return {
            questionId: this.question.id,
            count: this.question.answers_count,
            answers: [],
            nextURL: null
        }
    },
    methods: {
        fetch(endpoint) {
            axios.get(endpoint)
            .then(({data}) => {
                console.log(data);
                this.answers.push(...data.data);
                this.nextURL = data.next_page_url;
            })
            .catch((err) => console.log(err));
        }
    }
}
</script>