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
        item: {},
        search_query: null,
        search_items: null,
        categories: {},
        suppliers: {},
        export_modal: true,
        export_file: null,
        billing_modal: true
    },
    actions: {

        /*
         *  Get all data of SALES
         * ****************************/

        GET_ALL_SALES({ commit }) {
            commit('START_LOADING_SPINNER')
            axios.get('/api/'+ prefix  +'/sales').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_GET_ALL_SALES', res.data.data);
                    commit('END_LOADING_SPINNER')
                } else if (res.status === 204) {
                    commit('SET_GET_ALL_SALES', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_GET_ALL_SALES', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },


        /*
         *  Get all data of SALES by pagiante
         * *****************************************/

        GET_ALL_SALES_PAGINATE({ commit }, link) {
            commit('START_LOADING_SPINNER')
            axios.get(link).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_GET_ALL_SALES', res.data.data);
                    commit('END_LOADING_SPINNER')
                } else if (res.status === 204) {
                    commit('SET_GET_ALL_SALES', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_GET_ALL_SALES', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },


        /*
         *  Get categories of user
         * *****************************************/

        GET_BILLING({ commit }, id) {
            axios.get('/api/'+ prefix  +'/sales/' + id).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_BILLING', res.data.data);
                } else if (res.status === 204) {
                    commit('SET_BILLING', null);
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_BILLING', null);
                }
            })
        },



        /*
         *  Update Medicinal
         * *****************************************/

        UPDATE_BILLING({ commit }, item) {
            axios.post('/api/'+ prefix  +'/sales/update', item).then(res => {
                if (res.data.status === 'success') {
                    alertify.success('Successful update')

                } else if (res.status === 204) {
                    alertify.error(error.response.data.message);
                }
                
            }, error => {
                if (error.response.status === 500) {
                    alertify.error(error.response.data.message);
                }
            })
        },


        /*
         *  Upload new SALES
         * *****************************************/

        EXPORT_FILE_SALES({ commit }, id) {
            axios.post('/api/'+ prefix  +'/sales/export').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_EXPORT', { url: res.data.download_url });
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


        /*
         *  Search medicinal
         * *****************************************/

        SEARCH_SALES({ commit }, query) {
            commit('START_LOADING_SPINNER')
            axios.post('/api/'+ prefix  +'/sales/search', { query: query }).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SEARCH_SALES', res.data);
                    commit('END_LOADING_SPINNER')
                } else if (res.status === 204) {
                    commit('SET_SEARCH_SALES', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_SEARCH_SALES', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },


        /*
         *  Sort By SALES
         * ****************************/

        GET_SORTBY_SALES({ commit }, { type, category_id }) {
            commit('START_LOADING_SPINNER')
            axios.post('/api/'+ prefix  +'/SALES/sort', { type: type, category_id: category_id }).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SORTBY_SALES', res.data.data);
                    commit('END_LOADING_SPINNER')
                } else if (res.status === 204) {
                    commit('SET_SORTBY_SALES', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_SORTBY_SALES', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },

    },

    mutations: {

        /*
         * Set Data
         * Reference - GET_ALL_SALES()
         ************************************/

        SET_GET_ALL_SALES(state, data) {
            state.items = data;
        },


        /*
        * Set Medicinal Data
        * Reference - GET_MEDICINAL)
        ************************************/

        SET_BILLING(state, data) {
            state.item = data;
            state.billing_modal = false;
        },



        /*
         * EXPORT Medicinal
         * Reference - EXPORT()
         ************************************/

        SET_EXPORT(state, data) {
            state.export_file = data.url;
            state.export_modal = false;
        },

        /*
         * Search Medicinal
         * Reference - EXPORT()
         ************************************/
        SET_SEARCH_SALES(state, data) {
            if (data === null) {
                state.search_items = 'empty';
            } else {
                state.search_items = data;
            }
        },



        /*
         * Search Medicinal
         * Reference - EXPORT()
         ************************************/
        SET_SORTBY_SALES(state, data) {
            if (data === null) {
                state.items = 'empty';
            } else {
                state.items = data;
            }
        },

        /*
         * Set Empty Search Medicinal
         ************************************/
        EMPITY_SEARCH_SALES(state) {
            state.search_items = null;
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