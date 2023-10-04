import { createRouter, createWebHistory } from 'vue-router';
import HomePage from './pages/HomePage.vue';
import NotFound from './pages/NotFound.vue';
import SearchPage from './pages/SearchPage.vue';
import SingleApartment from './pages/SingleApartment.vue';
import ThankYouPage from './pages/ThankYou.vue';

const router = createRouter({
history: createWebHistory(),
routes: [
    {
        path: '/',
        name: 'home',
        component: HomePage
    },
    {
        path: '/SearchPage',
        name: 'search',
        component: SearchPage,
        props: (route) => ({
            n_rooms: route.query.n_rooms,
            n_beds: route.query.n_beds,
            address: route.query.address,
            range: route.query.range,
            services: route.query.services,
        }),
    },
    {
        path: '/determinato_appartamento/:slug',
        name: 'determinato_appartamento',
        component: SingleApartment
    },
    {
        path: '/thank-you',
        name: 'thank-you',
        component: ThankYouPage 
    },
    {
        path: '/pagina-non-trovata',
        name: 'not-found',
        component: NotFound
    },
    {
        path: '/:catchAll(.*)',
        redirect: '/pagina-non-trovata'
    }
]
});
export { router };