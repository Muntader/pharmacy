import Vue from 'vue';
import Auth from '../../packages/Auth';
import router from '../../packages/Routes';
import swal from 'sweetalert';
const alertify = require("alertify.js");

Vue.use(Auth);

// Check if user or subuser and change vuex store

let check = Vue.auth.getUserInfo('permission');

let prefix = 'u';
if (check == 1 || check == 2 || check == 3) {
    prefix = 's';
}else{
    prefix = 'u';
}



const module = {
    state: {
        loading: false,
        items: {},
        item: {},
    },
    actions: {

        /*
         *  Get categories of user
         * *****************************************/

        GET_ALL_CATEGORIES({ commit }, link) {
            alert(prefix);
            axios.get('/api/'+ prefix +'/categories').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_CATEGORIES', res.data.data);
                } else if (res.status === 204) {
                    commit('SET_CATEGORIES', null);
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_CATEGORIES', null);
                }
            })
        },





        /*
         *  Update Medicinal
         * *****************************************/

        UPDATE_CATEGORY({ commit }, {id,name}) {
            axios.post('/api/'+ prefix  +'/categories/update', {id,name}).then(res => {
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

        CREATE_CATEGORIES({ commit }, items) {
            axios.post('/api/'+ prefix  +'/categories/create', items).then(res => {
                if (res.data.status === 'success') {
                    alertify.success('Successful create')
                    commit('SET_NEW_CATEGORIES', res.data.data);
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

        DELETE_CATEGORY({ commit }, {id,index}) {
            axios.delete('/api/'+ prefix  +'/categories/delete/ '+ id).then(res => {
                if (res.data.status === 'success') {
                    alertify.success('Successful delete')
                    commit('SET_DELETE_CATEGORY', index);
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
         * Set Categories Data
         * Reference - GET_CATEGORIES()
         ************************************/

        SET_CATEGORIES(state, data) {
            state.items = data;
        },



        /*
         * Set New Medicinals
         * Reference - CREATE_MEDICINALS()
         ************************************/

        SET_NEW_CATEGORIES(state, data) {
            for (let index = 0; index < data.length; index++) {
                state.items.push(data[index]);
            }
        },



        SET_DELETE_CATEGORY(state,data){
            state.items.splice(data,1);

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