<script>
    export default {
        props: ['answer'],
        data() {
            return {
                editing: false,
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
            }
        },
        methods: {
            cancel() {
                this.body = this.beforeEditCache;
                this.editing = false;
            },
            edit() {
                this.beforeEditCache = this.body;
                this.editing = true;
            },
            update() {
                console.log()
                axios.patch(`/questions/${this.questionId}/answers/${this.id}`, {
                    body: this.body
                })
                .then((res) => {
                    this.editing = false;
                    this.bodyHtml = res.data.body_html
                    alert(res.data.message)
                })
                .catch((err) => {
                    console.log('err', err);
                });
            }
        }
    }
</script>