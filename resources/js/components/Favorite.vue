<template>
    <a 
        title="Click to mark as favorite (Click to unfavorite)" 
        :class="classes"
        @click.prevent="toggle"
    >
        <i class="fas fa-star fa-2x"></i>
        <span class="favorite-count">{{ count }}</span>
    </a>
</template>

<script>
export default {
    props: ['question'],
    data() {
        return {
            count: this.question.favorites_count,
            isFavorited: this.question.is_favorited,
            id: this.question.id
        }
    },
    computed: {
        classes() {
            return [
                'favorite', 'mt-4',
                !this.signedIn ? 'off' : (this.isFavorited ? 'favorited' : '')
            ];
        },
        endpoint() {
            return `/questions/${this.id}/favorites`;
        }
    },
    methods: {
        toggle() {
            if (!this.signedIn) {
                this.$toast.warning('Please login to favorite a question', 'Warning', {
                    timeout: 5000,
                    position: 'bottomLeft'
                });

                return;
            }
            this.isFavorited ? this.destroy() : this.create();
        },
        destroy() {
            axios.delete(this.endpoint)
            .then((res) => {
                this.count--;
                this.isFavorited = false;
            })
            .catch((err) => console.log(err)
            );
            
        },
        create() {
            axios.post(this.endpoint)
            .then((res) => {
                this.count++;
                this.isFavorited = true;
            })
            .catch((err) => console.log(err)
            );
            
        }
    }
}
</script>

