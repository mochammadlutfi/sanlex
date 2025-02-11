import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';
import { i18nVue } from 'laravel-vue-i18n';
import { VueQueryPlugin } from '@tanstack/vue-query'


const pinia = createPinia()

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

import BaseLayout from './Layouts/AuthenticatedLayout.vue';
import Breadcrumb from './Components/Breadcrumb.vue';
import { formatMixin } from './Mixins/formatMixin';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mixin(formatMixin)
            .use(pinia)
            .use(VueQueryPlugin)
            .use(ZiggyVue)
            .use(i18nVue, {
                lang: 'id',
                resolve: lang => {
                    const langs = import.meta.glob('../../lang/*.json', { eager: true });
                    return langs[`../../lang/${lang}.json`].default;
                },
            })
            .component('BaseLayout', BaseLayout)
            .component('Breadcrumb', Breadcrumb)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
