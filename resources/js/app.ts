import '../css/app.css';
import './bootstrap';

import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { setThemeOnLoad } from "@/theme";
import Vue3Toastify, { type ToastContainerOptions } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { createPinia } from 'pinia'
import Icon from '@/Components/Include/Icon.vue';
import VueApexCharts from 'vue3-apexcharts';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const pinia = createPinia();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(
                Vue3Toastify,
                {
                    autoClose: 3000,
                } as ToastContainerOptions,
            )
            .use(pinia)
            .use(VueApexCharts)
            .component('Link', Link)
            .component('Head', Head)
            .component('Icon', Icon)
            .mount(el);
    },
    progress: {
        color: '#ff2d20',
        includeCSS: true,
        showSpinner: false,
    },
});

setThemeOnLoad();
