<template>
    <a 
        title="Click to mark as favorite (Click to unfavorite)" 
        class="classes"
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
            signedIn: true
        }
    },
    computed: {
        classes() {
            return [
                'favorite', 'mt-4',
                !this.signedIn ? 'off' : (this.isFavorited ? 'favorited' : '')
            ];
        }
    }
}
</script>

