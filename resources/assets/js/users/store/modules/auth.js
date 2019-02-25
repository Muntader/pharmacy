import Vue from 'vue';
import router from '../../packages/Routes';
import swal from 'sweetalert';
const alertify = require("alertify.js");



const module = {
    state: {
        error: null,
        items: {},
        payment_update: {},
        plan: {},
        username_found: false,
        username_data: {},
        message: null,
        loading: true,
        button_loading: false,
    },
    actions: {

        /*
         *              Login
         * ************************************/
        LOGIN({ commit }, { email, password }) {
            commit('BUTTON_LOAD');
            var user = {
                grant_type: 'password',
                client_id: 2,
                client_secret: Vue.helper.client_secret(),
                username: email,
                password: password,
                scope: '',
                newProvider: 'api'
            };
            var subuser = {
                grant_type: 'password',
                client_id: 2,
                client_secret: Vue.helper.client_secret(),
                username: email,
                password: password,
                scope: '',
                newProvider: 'subuser'
            };

            /* First check if provider is user
             * IF error check if subuser provider
             * Then display message
             ***************************************/
            axios.post('/oauth/token', user).then(token => {
                axios.get('/api/user', { headers: { Authorization: 'Bearer ' + token.data.access_token } })
                    .then(info => {
                        if (info.data.status === 1000) {
                            Vue.auth.setToken(token.data.access_token, token.data.expires_in, info.data.name, info.data.image, 'admin');
                            window.location.replace('/register/payment');
                        } else {
                            Vue.auth.setToken(token.data.access_token, token.data.expires_in, info.data.name, info.data.image, 'admin');
                            router.go('/app');
                            commit('BUTTON_CLEAR');
                        }
                    });
            }, error => {
                if (error.response.status === 401) {
                    axios.post('/oauth/token', subuser).then(token => {
                        axios.get('/api/subuser', { headers: { Authorization: 'Bearer ' + token.data.access_token } })
                            .then(info => {
                                Vue.auth.setToken(token.data.access_token, token.data.expires_in, info.data.name, info.data.image, info.data.permission);
                                router.go('/app');
                                commit('BUTTON_CLEAR');
                            });
                    }, error => {
                        if (error.response.status === 401) {
                            commit('SET_ERROR')
                            commit('BUTTON_CLEAR')
                        } else {
                            commit('SET_ERROR')
                            commit('BUTTON_CLEAR')
                        }
                    });
                } else {
                    commit('SET_ERROR')
                    commit('BUTTON_CLEAR')
                }
            });
        },


        /*
         *     Check username before login
         * ************************************/
        CHECK_USERNAME({ commit }, username) {
            commit('BUTTON_LOAD');
            axios.post('/api/check/username', { username: username }).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_USERNAME_DATA', res.data.data)
                    commit('BUTTON_CLEAR');
                } else if (res.data.status === 'error') {
                    commit('SET_ERROR_MESSAGE', res.data.message)
                    commit('BUTTON_CLEAR');
                }
            })
        },


        /*
         *               Singup
         * ************************************/

        SIGNUP({ commit }, { name, username, email, password, confirm }) {
            commit('BUTTON_LOAD');
            axios.post('/api/register/create', { name: name, username: username, email: email, password: password, 'password_confirmation': confirm }).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_USERNAME_DATA', res.data.data)
                    router.push({ name: 'payment' })
                    commit('BUTTON_CLEAR');
                } else if (res.data.status === 'error') {
                    commit('SET_ERROR_MESSAGE', res.data.message)
                    commit('BUTTON_CLEAR');
                }
            })
        },

        /*
         *               Payment
         * ************************************/

        PAYMENT({ commit }, { plan_id, token }) {
            commit('BUTTON_LOAD');
            axios.post('/api/u/register/payment', { plan_id, token }).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_USERNAME_DATA', res.data.data)
                    commit('BUTTON_CLEAR');
                } else if (res.data.status === 'error') {
                    commit('SET_ERROR_MESSAGE', res.data.message)
                    commit('BUTTON_CLEAR');
                }
            })
        },


        /*
         * Send  code forget password to mail
         * ************************************/

        FORGET_SEND_MAIL({ commit }, { email }) {
            commit('BUTTON_LOAD');
            axios.post('/api/register/forgetpassword', { email }).then(res => {
                if (res.data.status === 'success') {
                    alertify.success("Successful send, please check your mail");
                    router.push({ name: 'home' })
                    commit('BUTTON_CLEAR');
                } else if (res.data.status === 'error') {
                    commit('SET_ERROR_MESSAGE', res.data.message)
                    commit('BUTTON_CLEAR');
                }
            })
        },

        /* 
         *      Recover Password
         * ************************************/

        FORGET_PASSWORD_CHANGE({ commit }, { password, confirm, email, hash }) {
            commit('BUTTON_LOAD');
            axios.post('/api/register/forget/recover', { email: email, password: password, 'password_confirmation': confirm, hash: hash }).then(res => {
                if (res.data.status === 'success') {
                    router.push({ name: 'login' })
                    commit('BUTTON_CLEAR');
                } else if (res.data.status === 'error') {
                    alertify.error("Error, Try again later ");
                    router.push({ name: 'login' });
                    commit('BUTTON_CLEAR');
                }
            }, error => {
                alertify.error("Error, Try again later ");
            })
        },

    },

    mutations: {
        SET_ERROR(state, error) {
            state.error = true;
        },

        SET_USERNAME_DATA(state, data) {
            state.username_data = data;
            state.username_found = true;
        },

        CLEAR_USERNAME_DATA(state, data) {
            state.username_data = undefined;
            state.username_found = false;
        },

        SET_ERROR_MESSAGE(state, data) {
            state.message = data;
        },

        SET_SUCCESS_MESSAGE(state, data) {
            state.message = data;
        },

        // Spiner load
        SPINER_LOAD(state) {
            state.loading = true;
        },

        SPINER_CLEAN(state) {
            state.loading = false;
        },
        // BUTTON load
        BUTTON_LOAD(state) {
            state.button_loading = true;
        },
        BUTTON_CLEAR(state) {
            state.button_loading = false;
        },
    },

}
export default module;