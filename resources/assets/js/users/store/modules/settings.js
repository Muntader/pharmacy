import Vue from 'vue';
import router from '../../packages/Routes';
import swal from 'sweetalert';
const alertify = require("alertify.js");

const module = {
    state: {
        loading: false,
        profile_item: {},
        payment_info: [],
        payment_update: [],
        payment_billing: [],
        invoice_item: [],
        button_loading: false,

    },
    actions: {

        /*
         *  Get USERS of user
         * *****************************************/

        GET_SETTING_USER_DETAILS({ commit }) {
            commit('START_LOADING_SPINNER')           
            axios.get('/api/u/settings/user/details').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SETTING_USER_DETAILS', res.data.data);
                    commit('END_LOADING_SPINNER')

                } else if (res.status === 204) {
                    commit('SET_SETTING_USER_DETAILS', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_SETTING_USER_DETAILS', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },

        /*
         *  Update Image
         * *****************************************/

        UPDATE_SETTING_USER_AVATAR({ commit }, formdata) {
            commit('START_LOADING_SPINNER')
            axios.post('/api/u/settings/user/details/image/change',formdata).then(res => {
                if (res.data.status === 'success') {
                    location.reload();
                    commit('END_LOADING_SPINNER')

                }
            }, error => {
                console.log(error.response)
                if (error.response.status === 500) {
                    alertify.error(error.response.data.message)
                    commit('END_LOADING_SPINNER')
                } else if (error.response.status === 422) {
                    alertify.error(error.response.data.message)
                    commit('END_LOADING_SPINNER')
                }
            })
        },


        /*
         *  Update Name-Username-Email
         * *****************************************/

        UPDATE_SETTING_USER_DETAILS({ commit }, { name, username, email, password, password_confirmation}) {
            axios.post('/api/u/settings/user/details/update', { name, username, email, password, password_confirmation }).then(res => {
                if (res.data.status === 'success') {
                    Vue.auth.destroyToken();
                    location.reload();
                    commit('END_LOADING_SPINNER')

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
         *  Update Password
         * *****************************************/

        UPDATE_SETTING_USER_PASSWORD({ commit }, {password, password_confirmation }) {
            axios.post('/api/u/settings/user/details/password', { password, password_confirmation }).then(res => {
                if (res.data.status === 'success') {
                    Vue.auth.destroyToken();
                    location.reload();
                    commit('END_LOADING_SPINNER')

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
         *  Get Payment Details
         * *****************************************/

        GET_SETTING_USER_PAYMENT_DETAILS({ commit }) {
            commit('START_LOADING_SPINNER')
            axios.get('/api/u/settings/user/details/payment').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SETTING_PAYMENT_DETAILS', res.data.data);
                    commit('END_LOADING_SPINNER')

                } else if (res.status === 204) {
                    commit('SET_SETTING_PAYMENT_DETAILS', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_SETTING_PAYMENT_DETAILS', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },


        /*
         *  Cancel Membership 
         * *****************************************/

        SETTING_CANCEL_MEMBERSHIP({ commit }) {
            commit('START_LOADING_BUTTON');
            swal({
                title: "Are you sure to cancel membership?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.get('/api/u/settings/user/details/payment/cancel').then(res => {
                            if (res.data.status === 'success') {
                                commit('SET_SETTING_CANCEL_MEMBERSHIP', res.data.data);
                                commit('END_LOADING_BUTTON');
                                alertify.success('Successful cancel')
                            } else if (res.status === 204) {
                                commit('END_LOADING_BUTTON');
                                alertify.error(error.response.data.message)
                            }
                        }, error => {
                            if (error.response.status === 500) {
                                commit('END_LOADING_BUTTON');
                                alertify.error(error.response.data.message)
                            }
                        })
                    }
                });

        },


    /*
     *  Cancel Membership 
     * *****************************************/

        SETTING_RESUME_MEMBERSHIP({ commit }) {
            commit('START_LOADING_BUTTON');
                        axios.get('/api/u/settings/user/details/payment/resume').then(res => {
                            if (res.data.status === 'success') {
                                commit('SET_SETTING_RESUME_MEMBERSHIP', res.data.data);
                                commit('END_LOADING_BUTTON');
                                alertify.success('Successful resume')
                            } else if (res.status === 204) {
                                commit('END_LOADING_BUTTON');
                                alertify.error(error.response.data.message)
                            }
                        }, error => {
                            if (error.response.status === 500) {
                                commit('END_LOADING_BUTTON');
                                alertify.error(error.response.data.message)
                            }
                        })


        },    

          /*
           *  Get Payment billing 
           * *****************************************/

        GET_SETTING_BILLING_DETAILS({ commit }) {
            commit('START_LOADING_SPINNER')
            axios.get('/api/u/settings/user/details/payment/billing').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SETTING_PAYMENT_BILLING', res.data.data);
                    commit('END_LOADING_SPINNER')

                } else if (res.status === 204) {
                    commit('SET_SETTING_PAYMENT_BILLING', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_SETTING_PAYMENT_BILLING', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },

        /*
         *  Cancel Membership 
         * *****************************************/

        SETTING_UPDATE_PAYMNET_CARD({ commit }, token) {
            commit('START_LOADING_BUTTON');
            axios.post('/api/u/settings/user/details/payment/update', { token}).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SETTING_RESUME_MEMBERSHIP', res.data.data);
                    commit('END_LOADING_BUTTON');
                    alertify.success('Successful update your payment card')
                } else if (res.status === 204) {
                    commit('END_LOADING_BUTTON');
                    alertify.error(error.response.data.message)
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('END_LOADING_BUTTON');
                    alertify.error(error.response.data.message)
                }
            })


        },            
        

        /*
         *  Get Payment billing 
         * *****************************************/

        GET_SETTING_INVOCIE_DETAILS({ commit }) {
            commit('START_LOADING_SPINNER')
            axios.get('/api/u/settings/user/details/invoice').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SETTING_INVOICE_DETAILS', res.data.data);
                    commit('END_LOADING_SPINNER')

                } else if (res.status === 204) {
                    commit('SET_SETTING_INVOICE_DETAILS', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_SETTING_INVOICE_DETAILS', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },

        /*
         *  Cancel Membership 
         * *****************************************/

        UPDATE_SETTING_INVOICE_DETAILS({ commit }, item) {
            commit('START_LOADING_BUTTON');
            axios.post('/api/u/settings/user/details/invoice/update', { item }).then(res => {
                if (res.data.status === 'success') {
                    commit('END_LOADING_BUTTON');
                    alertify.success('Successful update')
                } else if (res.status === 204) {
                    commit('END_LOADING_BUTTON');
                    alertify.error(error.response.data.message)
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('END_LOADING_BUTTON');
                    alertify.error(error.response.data.message)
                }
            })
        },
    },

    mutations: {



        /*
         * Set USERS Data
         * Reference - GET_USERS()
         ************************************/

        SET_SETTING_USER_DETAILS(state, data) {
            state.profile_item = data;
        },



        /*
         * Set payment data
         * Reference - GET_USERS()
         ************************************/

        SET_SETTING_PAYMENT_DETAILS(state, data) {
            state.payment_info = data;
            state.payment_update = data;
        },

        /*
         * Set Cancel Payment
         * Reference - GET_USERS()
         ************************************/
        SET_SETTING_CANCEL_MEMBERSHIP(state, data) {
            state.payment_update = data;
        },


        /*
         * Set Resume Payment
         * Reference - GET_USERS()
         ************************************/
        SET_SETTING_RESUME_MEMBERSHIP(state, data) {
            state.payment_update = data;
        },



        /*
         * Set payment billing
         * Reference - GET_USERS()
         ************************************/

        SET_SETTING_PAYMENT_BILLING(state, data) {
            state.payment_billing = data;
        },



        SET_SETTING_INVOICE_DETAILS(state, data){
            state.invoice_item = data;
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



        /*
         * Start Loading Spinner
         ************************************/

        START_LOADING_BUTTON(state) {
            state.button_loading = true;
        },

        /*
         * END Loading Spinner
         ************************************/

        END_LOADING_BUTTON(state) {
            state.button_loading = false;
        },
    },

}
export default module;