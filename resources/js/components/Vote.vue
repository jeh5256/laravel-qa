<template>
    <div class="d-flex flex-column vote-controls">
        <a 
            :title="title('up')" 
            class="up-vote" :class="classes"
            @click.prevent="upVote"
        >
            <i class="fas fa-caret-up fa-3x"></i>
        </a>
        
        <span class="votes-count">{{ count }}</span>
        <a 
            :title="title('down')" 
            class="down-vote" :class="classes"
            @click.prevent="downVote"
        >
            <i class="fas fa-caret-down fa-3x"></i>
        </a>
        
        <favorite v-if="name === 'question'" :question="model"></favorite>
        <accept v-else :answer="model"></accept>
    </div>
</template>
<script>
import Favorite from './Favorite';
import Accept from './Accept';

export default {
    props: ['name', 'model'],
    components: {
        Favorite,
        Accept
    },
    computed: {
        classes() {
            return this.signedIn ? '' : '';
        },
        endpoint() {
            return `/${this.name}s/${this.id}/vote`;
        }
    },
    data() {
        return {
            count: this.model.vote_count || 0,
            id: this.model.id
        }
    },
    methods: {
        _vote(vote) {

            if (!this.signedIn) {
                this.$toast.warning(`Please login to vote for this ${this.name}`, 'Warning', {
                    timeout: 5000,
                    position: 'bottomLeft'
                });

                return;
            }

            axios.post(this.endpoint, { vote })
            .then((res) => {

                if (this.count != res.data.votesCount) {
                    this.$toast.success(res.data.message, 'Success', {
                    timeout: 5000,
                    position: 'bottomLeft'
                    });
                }
                
                this.count = res.data.votesCount;
            })
            .catch((err) => {
                this.$toast.error('Vote failed', 'Error', {
                    timeout: 5000,
                    position: 'bottomLeft'
                });
            });
        },
        downVote() {
            this._vote(-1);
        },
        upVote() {
            this._vote(1);
        },
        title(voteType) {
            let titles = {
                up: `This ${this.name} is useful`,
                down: `This ${this.name} is not useful`
            };

            return titles[voteType];
        }
    },
}
</script>
