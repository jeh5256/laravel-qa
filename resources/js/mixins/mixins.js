import highlight from './highlight';

export default {
    data() {
        return {
            editing: false
        }
    },
    methods: {
        cancel() {
            this.restoreFromCache();
            this.editing = false;
        },
        edit() {
            this.setEditCache();
            this.editing = true;
        }, 
        delete() {}, 
        destroy() {
            this.$toast.question('Are you sure about that?', "Confirm", {
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: 'once',
                id: 'question',
                zindex: 999,
                title: 'Hey',
                position: 'center',
                buttons: [
                    ['<button><b>YES</b></button>', (instance, toast) => {

                        this.delete();
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
            
                    }, true],
                    ['<button>NO</button>', function (instance, toast) {
            
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
            
                    }],
                ],
            });
        },
        payload() {},
        restoreFromCache() {},
        setEditCache() {},
        update() {
            axios.put(this.endpoint, this.payload())
            .then(({data}) => {
                this.bodyHtml = data.body_html;
                this.editing = false;
                this.$toast.success(data.message, "Success", { timeout: 5000 });
            })
            .then(() => {
                 this.highlight();
            })
            .catch(({response}) => {
                this.$toast.error(response.data.message, 'Error', { timeout: 5000 });
            });
        }
    },
    mixins: [highlight]
}