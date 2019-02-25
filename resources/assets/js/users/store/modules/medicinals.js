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
        export_file:null,
    },
    actions: {

        /*
         *  Get all data of medicinals
         * ****************************/

        GET_ALL_MEDICINALS({commit}) {
            commit('START_LOADING_SPINNER')
            axios.get('/api/'+ prefix  +'/medicinals').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_GET_ALL_MEDICINALS',res.data.data);
                    commit('END_LOADING_SPINNER')
                }else if(res.status === 204){
                    commit('SET_GET_ALL_MEDICINALS', null);
                    commit('END_LOADING_SPINNER')
                }
            },error => {
                if (error.response.status === 500){
                    commit('SET_GET_ALL_MEDICINALS', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },


        /*
         *  Get all data of medicinals by pagiante
         * *****************************************/

        GET_ALL_MEDICINALS_PAGINATE({ commit }, link) {
            commit('START_LOADING_SPINNER')
            axios.get(link).then(res => {
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
         *  Get categories of user
         * *****************************************/

        GET_CATEGORIES({ commit }, link) {
            axios.get('/api/'+ prefix  +'/categories').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_CATEGORIES', res.data.data);
                } else if (res.status === 204) {
                    commit('SET_CATEGORIES', null);
                }
            }, error => {
                if (error.response.status=== 500) {
                    commit('SET_CATEGORIES', null);
                }
            })
        },


        /*
         *  Get categories of user
         * *****************************************/

        GET_SUPPLIERS({ commit }, link) {
            axios.get('/api/'+ prefix  +'/suppliers').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SUPPLIERS', res.data.data);
                } else if (res.status === 204) {
                    commit('SET_SUPPLIERS', null);
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_SUPPLIERS', null);
                }
            })
        },

        /*
         *  Get categories of user
         * *****************************************/

        GET_MEDICINAL({ commit }, id) {
            axios.get('/api/'+ prefix  +'/medicinals/'+ id).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_MEDICINAL', res.data.data);
                } else if (res.status === 204) {
                    commit('SET_MEDICINAL', null);
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_MEDICINAL', null);
                }
            })
        },



        /*
         *  Update Medicinal
         * *****************************************/

        UPDATE_MEDICINAL({ commit }, formData) {
            axios.post('/api/'+ prefix  +'/medicinals/update',formData).then(res => {
                if (res.data.status === 'success') {
                    alertify.success('Successful update')
                    commit('SET_UPDATE_MEDICINAL', {data:res.data.data});
                } else if (res.status === 204) {
                    commit('SET_UPDATE_MEDICINAL', null);
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_UPDATE_MEDICINAL', null);
                }
            })
        },


        /*
         *  Upload new medicinals
         * *****************************************/

        CREATE_MEDICINALS({ commit }, formData) {
            axios.post('/api/'+ prefix  +'/medicinals/create', formData).then(res => {
                if (res.data.status === 'success') {
                    alertify.success('Successful upload')
                    commit('SET_NEW_MEDICINALS',res.data.data);
                }
            }, error => {
                console.log(error.response)
                if (error.response.status === 500) {
                    alertify.error(error.response.data.message)
                } else if (error.response.status === 422) {
                    alertify.error(error.response.data.message)
                }
            })
        },


        /*
         *  Upload new medicinals
         * *****************************************/

        EXPORT_FILE_MEDICINALS({ commit }, id) {
            axios.post('/api/'+ prefix  +'/medicinals/export').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_EXPORT_MEDICINALS', {url:res.data.download_url});
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

        SEARCH_MEDICINALS({ commit }, query) {
           commit('START_LOADING_SPINNER')
           axios.post('/api/'+ prefix  +'/medicinals/search', {query:query}).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SEARCH_MEDICINALS', res.data);
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

        GET_SORTBY_MEDICINALS({commit},{type, category_id}) {
            commit('START_LOADING_SPINNER')
            axios.post('/api/'+ prefix  +'/medicinals/sort',{type: type, category_id: category_id }).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SORTBY_MEDICINALS',res.data.data);
                    commit('END_LOADING_SPINNER')
                }else if(res.status === 204){
                    commit('SET_SORTBY_MEDICINALS', null);
                    commit('END_LOADING_SPINNER')
                }
            },error => {
                if (error.response.status === 500){
                    commit('SET_SORTBY_MEDICINALS', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },

    },

    mutations: {

        /*
         * Set Data
         * Reference - GET_ALL_MEDICINALS()
         ************************************/

        SET_GET_ALL_MEDICINALS (state,data){
            state.items = data;
        },


        /*
         * Set Categories Data
         * Reference - GET_CATEGORIES()
         ************************************/

        SET_CATEGORIES(state, data) {
            state.categories = data;
        },

        /*
        * Set Categories Data
        * Reference - GET_CATEGORIES()
        ************************************/

        SET_SUPPLIERS(state, data) {
            state.suppliers = data;
        },


        /*
        * Set Medicinal Data
        * Reference - GET_MEDICINAL)
        ************************************/

        SET_MEDICINAL(state, data) {
            state.item = data;
        },


        /*
         * Update Medicinal
         * Reference - UPDATE_MEDICINAL()
         ************************************/

        SET_UPDATE_MEDICINAL(state, data) {
            state.items.push(data);
        },

        /*
         * Set New Medicinals
         * Reference - CREATE_MEDICINALS()
         ************************************/

        SET_NEW_MEDICINALS(state, data) {
            state.items.push(data);
        },

        /*
         * EXPORT Medicinal
         * Reference - EXPORT()
         ************************************/

        SET_EXPORT_MEDICINALS(state, data) {
            state.export_file = data.url;
            state.export_modal = false;
        },

        /*
         * Search Medicinal
         * Reference - EXPORT()
         ************************************/
        SET_SEARCH_MEDICINALS(state, data) {
            if(data === null){
                state.search_items = 'empty';
            } else {
                state.search_items = data;
            }
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
        EMPITY_SEARCH_MEDICINALS(state) {
            state.search_items = null;
        },
        /*
         * Start Loading Spinner
         ************************************/

        START_LOADING_SPINNER (state){
            state.loading = true;
        },

        /*
         * END Loading Spinner
         ************************************/

        END_LOADING_SPINNER (state){
            state.loading = false;
        },
    },

}
export default module;