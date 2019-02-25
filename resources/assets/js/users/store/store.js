import Vue from 'vue';
import Vuex from 'vuex';
import Auth from '../packages/Auth';
import auth from './modules/auth'
import medicinals from './modules/medicinals'
import pos from './modules/pos'
import sales from './modules/sales'
import categories from './modules/categories'
import suppliers from './modules/suppliers'
import customers from './modules/customers'

import users from './modules/users'
import settings from './modules/settings'

Vue.use(Vuex);

export default new Vuex.Store({
    namespaced: true,
    strict: false,
    modules: {
        auth,
        medicinals,
        sales,
        categories,
        suppliers,
        pos,
        customers,
        users,
        settings,
    }
});
