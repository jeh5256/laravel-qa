<template>
    <div>   
        <a v-if="canAccept" title="Click to mark as best answer" 
            :class="classes"
            @click.prevent="create"
        >
            <i class="fas fa-check fa-2x"></i>
        </a>
        <a v-if="accepted" title="Accepted as best answer" :class="classes">
            <i class="fas fa-check fa-2x"></i>
        </a>
    </div>
</template>
<script>
export default {
    props: ['answer'],
    data() {
        return {
            isBest: this.answer.is_best_answer,
            id: this.answer.id
        }
    },
    computed: {
        canAccept() {
            return this.authorize('accept', this.answer);
        },
        accepted() {
            return !this.canAccept && this.answer.isBest;
        },
        classes() {
            return [
                'mt-2',
                this.isBest? 'vote-accepted' : ''
            ];
        }
    },
    methods: {
        create() {
            axios.post(`/answers/${this.id}/accept`)
            .then((res) => {
                this.$toast.success(res.data.message, 'Success', {
                    timeout: 5000,
                    position: 'bottomLeft'
                });
                this.isBest = true;
            })
            .catch((err) => console.log(err)
            );
        }
    }
}
</script>