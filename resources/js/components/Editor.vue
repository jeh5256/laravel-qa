<template>
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#text">Text</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#preview">Preview</a>
                </li>
            </ul>
        </div>
        <div class="card-body tab-content">
            <div class="tab-pane active" id="text">
                <slot></slot>
            </div>
            <div class="tab-pane" v-html="preview" id="preview"></div>
        </div>
    </div>
</template>
<script>
    import autosize from 'autosize';
    import MarkdownIt from 'markdown-it';
    import prism from 'markdown-it-prism';

    const md = new MarkdownIt();
    md.use(prism);

    export default {
       props: ['body'],
       computed: {
           preview() {
               return md.render(this.body);
           }
       },
       mounted() {
          autosize(this.$el.querySelector('textarea'));
       }
    }
</script>