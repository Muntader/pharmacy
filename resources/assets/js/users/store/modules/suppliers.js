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
    },
    actions: {

        /*
         *  Get SUPPLIERS of user
         * *****************************************/

        GET_ALL_SUPPLIERS({ commit }, link) {
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
         *  Update Medicinal
         * *****************************************/

        UPDATE_SUPPLIER({ commit }, { id, name, address, telephone, fax }) {
            axios.post('/api/'+ prefix  +'/supplier/update', { id, name, address, telephone, fax  }).then(res => {
                if (res.data.status === 'success') {
                    alertify.success('Successful update')
                } else if (res.status === 204) {
                    alertify.error(error.response.data.message)
                }
            }, error => {
                if (error.response.status === 500) {
                    alertify.error(error.response.data.message)
                }
            })
        },


        /*
         *  Upload new medicinals
         * *****************************************/

        CREATE_SUPPLIER({ commit }, items) {
            axios.post('/api/'+ prefix  +'/supplier/create', items).then(res => {
                if (res.data.status === 'success') {
                    alertify.success('Successful create')
                    commit('SET_NEW_SUPPLIER', res.data.data);
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

        DELETE_SUPPLIER({ commit }, { id, index }) {
            axios.delete('/api/'+ prefix  +'/supplier/delete/ ' + id).then(res => {
                if (res.data.status === 'success') {
                    alertify.success('Successful delete')
                    commit('SET_DELETE_SUPPLIER', index);
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
    },

    mutations: {



        /*
         * Set SUPPLIERS Data
         * Reference - GET_SUPPLIERS()
         ************************************/

        SET_SUPPLIERS(state, data) {
            state.items = data;
        },



        /*
         * Set New Medicinals
         * Reference - CREATE_MEDICINALS()
         ************************************/

        SET_NEW_SUPPLIER(state, data) {
             state.items.push(data);
        },



        SET_DELETE_SUPPLIER(state, data) {
            state.items.splice(data, 1);

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