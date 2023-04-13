import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import withUUID from "vue-uuid";
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

import "primevue/resources/themes/lara-light-indigo/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";
import "primeflex/primeflex.css";
import "../css/main.css";

createInertiaApp({
    progress: {
        color: '#4B5563'
    },
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return withUUID(
            createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(PrimeVue)
                .use(ToastService)
                .use(ZiggyVue, Ziggy)
                .mount(el)
        );
    },
});
