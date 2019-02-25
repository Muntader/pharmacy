require('../bootstrap');
import Vue from 'vue';
import VueProgressBar from 'vue-progressbar';
import App from './views/app.vue'
import store from './store/store.js';
import router from './packages/Routes';
import i18n from './packages/Language';
import Auth from './packages/Auth';
import Helper from './packages/Helper';
import VTooltip from 'v-tooltip';
Vue.use(Helper);
Vue.use(Auth);


axios.defaults.headers.common['Authorization'] = 'Bearer ' + Vue.auth.getToken();


const options = {
    color: '#2ecc71',
    failedColor: '#F44336',
    thickness: '4px',
    transition: {
        speed: '0.5s',
        opacity: '0.8s',
        termination: 400
    },
    autoRevert: true,
    location: 'top',
    inverse: false
};

Vue.use(VueProgressBar, options)
Vue.use(VTooltip)


// if user is authenticated
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.userAuth)) {
        if (Vue.auth.isAuthenticated()) {
            next({
                name: 'app'
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
});

// if user is authenticated
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.notUserAuth)) {
        if (!Vue.auth.isAuthenticated()) {
            next({
                path: '/'
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
});

// 404
router.beforeEach((to, from, next) => {
    if (!to.matched.length) {
        next('/');
    } else {
        next();
    }
});




const app = new Vue({
    el: '#app',
    i18n,
    store,
    router,
    render: h => h(App)
});
