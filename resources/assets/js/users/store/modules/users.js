import Vue from 'vue';
import router from '../../packages/Routes';
import swal from 'sweetalert';
const alertify = require("alertify.js");

const module = {
    state: {
        loading: false,
        items: {},
        item: {},
    },
    actions: {

        /*
         *  Get USERS of user
         * *****************************************/

        GET_ALL_USERS({ commit }, link) {
            axios.get('/api/u/users').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_USERS', res.data.data);
                } else if (res.status === 204) {
                    commit('SET_USERS', null);
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_USERS', null);
                }
            })
        },





        /*
         *  Update Medicinal
         * *****************************************/

        UPDATE_USER({ commit }, { id, name, email, permission, status, password}) {

            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.post('/api/u/users/update', { id, name, email, permission, status, password }).then(res => {
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
                    }
                });

    
        },


        /*
         *  Upload new medicinals
         * *****************************************/

        CREATE_USER({ commit }, subuser) {
            axios.post('/api/u/users/create', subuser).then(res => {
                if (res.data.status === 'success') {
                    alertify.success('Successful create')
                    commit('SET_NEW_USER', res.data.data);
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

        DELETE_USER({ commit }, { id, index }) {
            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {

                    axios.delete('/api/u/users/delete/'+id).then(res => {
                        if (res.data.status === 'success') {
                            alertify.success('Successful delete')
                            commit('SET_DELETE_USER', index);
                        }
                    }, error => {
                        console.log(error.response)
                        if (error.response.status === 500) {
                            alertify.error(error.response.data.message)
                        } else if (error.response.status === 422) {
                            alertify.error(error.response.data.message)
                        }
                    })
                }
        });
        },
    },

    mutations: {



        /*
         * Set USERS Data
         * Reference - GET_USERS()
         ************************************/

        SET_USERS(state, data) {
            state.items = data;
        },



        /*
         * Set New Medicinals
         * Reference - CREATE_MEDICINALS()
         ************************************/

        SET_NEW_USER(state, data) {
            state.items.push(data);
        },



        SET_DELETE_USER(state, data) {
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