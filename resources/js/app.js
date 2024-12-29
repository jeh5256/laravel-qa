import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faStar, faArrowUp, faArrowDown, faCheck } from "@fortawesome/free-solid-svg-icons";
import { faStar as farStar} from '@fortawesome/free-regular-svg-icons';
import 'vue-toast-notification/dist/theme-sugar.css';
import CKEditor from '@ckeditor/ckeditor5-vue';
import ToastPlugin from 'vue-toast-notification';

library.add(faStar, farStar, faArrowUp, faArrowDown, faCheck);

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(CKEditor)
            .use(ToastPlugin)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mixin({ methods: { route } })
            .mount(el);
    },
    progress: {
        color: '#29d',
    }
});
