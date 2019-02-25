import Vue from 'vue';
import Auth from '../../packages/Auth';
import router from '../../packages/Routes';
import swal from 'sweetalert';
const alertify = require("alertify.js");

Vue.use(Auth);

// Check if user or subuser and change vuex store

let prefix = Vue.auth.getUserInfo('prefix');


const module = {
    state: {
        loading: false,
        items: {},
        invoice_number: false,
        categories: {},
    },
    actions: {

        /*
         *  Get all data of medicinals
         * ****************************/

        GET_ALL_MEDICINALS_POS({ commit }) {
            commit('START_LOADING_SPINNER')
            axios.get('/api/'+ prefix  +'/pos').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_GET_ALL_MEDICINALS', res.data.data);
                    commit('END_LOADING_SPINNER')
                } else if (res.status === 204) {
                    commit('SET_GET_ALL_MEDICINALS', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_GET_ALL_MEDICINALS', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },


        /*
         *  Search medicinal
         * *****************************************/

        SEARCH_MEDICINALS_POS({ commit }, query) {
            commit('START_LOADING_SPINNER')
            axios.post('/api/'+ prefix  +'/medicinals/search', { query: query }).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SEARCH_MEDICINALS', res.data.data);
                    commit('END_LOADING_SPINNER')
                } else if (res.status === 204) {
                    commit('SET_SEARCH_MEDICINALS', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_SEARCH_MEDICINALS', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },


        /*
         *  Sort By medicinals
         * ****************************/

        GET_SORTBY_MEDICINALS_POS({ commit }, { type, category_id }) {
            commit('START_LOADING_SPINNER')
            axios.post('/api/'+ prefix  +'/pos/sort', { type: type, category_id: category_id }).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SORTBY_MEDICINALS', res.data.data);
                    commit('END_LOADING_SPINNER')
                } else if (res.status === 204) {
                    commit('SET_SORTBY_MEDICINALS', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_SORTBY_MEDICINALS', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },


        /*
         *  Sort By medicinals
         * ****************************/

        CREATE_NEW_PAYMENT({ commit }, { customer_name, customer_phone,billing_number,total_price,order }) {
            commit('START_LOADING_SPINNER')
            axios.post('/api/'+ prefix  +'/pos/create', { customer_name:customer_name, customer_phone:customer_phone, billing_number:billing_number, total_price:total_price, customer_order:order }).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SUCCESS_SELL', res.data.invoice_number)
                    alertify.success('Successful Payment')
                } 
            }, error => {
                console.log(error.response)
                if (error.response.status === 500) {
                    alertify.error(error.response.data.message);
                } else if (error.response.status === 422) {
                    alertify.error(error.response.data.message);
                }
            })
        },

    },

    mutations: {

        /*
         * Set Data
         * Reference - GET_ALL_MEDICINALS()
         ************************************/

        SET_GET_ALL_MEDICINALS(state, data) {
            state.items = data;
        },


        /*
         * Search Medicinal
         * Reference - EXPORT()
         ************************************/
        SET_SEARCH_MEDICINALS(state, data) {
            if (data === null) {
                state.items = 'empty';
            } else {
                state.items = data;
            }
        },


        /*
         * Set Categories Data
         * Reference - GET_CATEGORIES()
         ************************************/

        SET_CATEGORIES(state, data) {
            state.categories = data;
        },


        /*
         * Search Medicinal
         * Reference - EXPORT()
         ************************************/
        SET_SORTBY_MEDICINALS(state, data) {
            if (data === null) {
                state.items = 'empty';
            } else {
                state.items = data;
            }
        },

        /*
         * Set Empty Search Medicinal
         ************************************/
        EMPITY_SEARCH_MEDICINALS_POS(state) {
            state.items = null;
        },

        SET_SUCCESS_SELL(state, data) {
            state.invoice_number = data;
        },

        /*
         * Start Loading Spinner
         ************************************/

        START_LOADING_SPINNER(state) {
            state.loading = true;
        },

        /*
         * END Loading Spinner
         ************************************/

        END_LOADING_SPINNER(state) {
            state.loading = false;
        },
    },

}
export default module;