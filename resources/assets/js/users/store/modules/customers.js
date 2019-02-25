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
        order_item: {},
        billing_modal: true,
        invoice_number: null,
        search_items: null,
        export_modal: true,
        export_file: null,
    },
    actions: {

        /*
         *  Get CUSTOMERS of user
         * *****************************************/

        GET_ALL_CUSTOMERS({ commit }, link) {
            commit('START_LOADING_SPINNER')
            axios.get('/api/' + prefix + '/customers').then(res => {
                if (res.data.status === 'success') {
                    commit('SET_ALL_CUSTOMERS', res.data.data);
                    commit('END_LOADING_SPINNER')
                } else if (res.status === 204) {
                    commit('SET_CALL_USTOMERS', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_ALL_CUSTOMERS', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },


        /*
         *  Get all data of medicinals by pagiante
         * *****************************************/

        GET_ALL_CUSTOMERS_PAGINATE({ commit }, link) {
            commit('START_LOADING_SPINNER')
            axios.get(link).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_GET_ALL_CUSTOMERS', res.data.data);
                    commit('END_LOADING_SPINNER')
                } else if (res.status === 204) {
                    commit('SET_GET_ALL_CUSTOMERS', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_GET_ALL_CUSTOMERS', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },

        /*
         *  Get categories of user
         * *****************************************/

        GET_CUSTOMER({ commit }, id) {
            commit('START_LOADING_SPINNER')
            axios.get('/api/' + prefix + '/customer/' + id).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_CUSTOMER', res.data.data);
                    commit('END_LOADING_SPINNER')
                } else if (res.status === 204) {
                    commit('SET_CUSTOMER', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_CUSTOMER', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },


        /*
         *  Update Medicinal
         * *****************************************/

        UPDATE_CUSTOMER({ commit }, { customer_id, name, address, telephone, info }) {
            axios.post('/api/' + prefix + '/customer/update', { customer_id, name, address, telephone,info }).then(res => {
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

        CREATE_CUSTOMER({ commit }, items) {
            axios.post('/api/' + prefix + '/customer/create', items).then(res => {
                if (res.data.status === 'success') {
                    alertify.success('Successful create')
                    commit('SET_NEW_CUSTOMER', res.data.data);
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

        DELETE_CUSTOMER({ commit }, { customer_id, index }) {
            axios.delete('/api/' + prefix + '/customer/delete/'+customer_id).then(res => {
                if (res.data.status === 'success') {
                    alertify.success('Successful delete')
                    commit('SET_DELETE_CUSTOMER', index);
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
         *  Search medicinal
         * *****************************************/

        SEARCH_CUSTOMERS({ commit }, query) {
            commit('START_LOADING_SPINNER')
            axios.post('/api/' + prefix + '/customer/search', { query: query }).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_SEARCH_CUSTOMERS', res.data);
                    commit('END_LOADING_SPINNER')
                } else if (res.status === 204) {
                    commit('SET_SEARCH_CUSTOMERS', null);
                    commit('END_LOADING_SPINNER')
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_SEARCH_CUSTOMERS', null);
                    commit('END_LOADING_SPINNER')
                }
            })
        },

        /*
         *  Sort By medicinals
         * ****************************/

        CREATE_NEW_CUSTOMER_PAYMENT({ commit }, { id, order, total_price}) {
            commit('START_LOADING_SPINNER')
            axios.post('/api/' + prefix + '/customer/payment/create', { id: id, customer_order: order, total_price: total_price }).then(res => {
                if (res.data.status === 'success') {
                    commit('INVOICE_INFO', res.data.customer_invoiceno)
                    alertify.success('Successful Payment')
                }
            }, error => {
                if (error.response.status === 500) {
                    alertify.error(error.response.data.message)
                } else if (error.response.status === 422) {
                    alertify.error(error.response.data.message)
                }
            })
        },

        /*
         *  Get categories of user
         * *****************************************/

        GET_CUSTOMER_BILLING({ commit }, {customer_id,invoice_number}) {
            axios.post('/api/' + prefix + '/customer/billing', { customer_id, invoice_number}).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_CUSTOMER_BILLING', res.data.data);
                } else if (res.status === 204) {
                    commit('SET_CUSTOMER_BILLING', null);
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_CUSTOMER_BILLING', null);
                }
            })
        },


        SELL_ALERADY_ORDER({ commit }, {customer_id,invoice_number}) {
            axios.post('/api/' + prefix + '/customer/sell/order', { customer_id, invoice_number}).then(res => {
                if (res.data.status === 'success') {
                    commit('SET_CUSTOMER_BILLING', res.data.data);
                } else if (res.status === 204) {
                    commit('SET_CUSTOMER_BILLING', null);
                }
            }, error => {
                if (error.response.status === 500) {
                    commit('SET_CUSTOMER_BILLING', null);
                }
            })
        },
    },

    mutations: {



        /*
         * Set CUSTOMERS Data
         * Reference - GET_ALL_CUSTOMERS()
         ************************************/

        SET_ALL_CUSTOMERS(state, data) {
            state.items = data;
        },


        /*
         * Set Customer Show
         * Reference - GET_CUSTOMER()
         ************************************/

        SET_CUSTOMER(state, data) {
            state.item = data;
        },
        

        /*
         * Set New Medicinals
         * Reference - CREATE_MEDICINALS()
         ************************************/

        SET_NEW_CUSTOMER(state, data) {
            state.items.data.push(data);
        },



        SET_DELETE_CUSTOMER(state, data) {
            state.items.data.splice(data, 1);

        },


        /*
         * Search Customers
         * Reference - EXPORT()
         ************************************/
        SET_SEARCH_CUSTOMERS(state, data) {
            if (data === null) {
                state.search_items = 'empty';
            } else {
                state.search_items = data;
            }
        },

        /*
         * Set Empty Search Medicinal
         ************************************/
        EMPITY_SEARCH_CUSTOMERS(state) {
            state.search_items = null;
        },
        

        INVOICE_INFO(state,data){
            state.invoice_number = data;
        },

        /*
        * Set Billing Data
        * Reference - GET_CUSTOMER_BILLING()
        ************************************/

        SET_CUSTOMER_BILLING(state, data) {
            state.order_item = data;
            state.billing_modal = false;
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