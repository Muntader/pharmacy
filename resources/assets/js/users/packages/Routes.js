import Vue from 'vue';

import VueRouter from 'vue-router';


let routes = [


    //  home
    {
        name: 'home',
        path: '/',
        component: require('../views/home'),
    },

    // Login
    {
        name: 'login',
        path: '/login',
        component: require('../views/auth/login'),
        meta: { userAuth: true }
    },

    // Plan 
    {
        name: 'plan',
        path: '/register/plan',
        component: require('../views/auth/plan'),
        meta: { userAuth: true }
    },

    /*
     * Signup
     *********************/
    {
        name: 'signup',
        path: '/register/signup',
        component: require('../views/auth/signup'),
        meta: { userAuth: true }
    },

    /*
     * Payment
     *********************/
    {
        name: 'payment',
        path: '/register/payment',
        component: require('../views/auth/payment'),
        meta: { userAuth: true }
    },

    /*
     * Forget Password Form
     *********************/
    {
        name: 'forget',
        path: '/register/forget',
        component: require('../views/auth/forget'),
        meta: { userAuth: true }
    },


    /*
     * Forget Check Hash
     *********************/
    {
        name: 'check_hash',
        path: '/register/forget/:hash',
        component: require('../views/auth/forget_check_hash'),
        meta: { userAuth: true }
    },



    /*
     * Dashboard 
     *********************/ 
    {
        name: 'app',
        path: '/app',
        component: require('../views/panel/dashboard'),
        meta: { userAuth: false }
    },

    /*
     * Medicinals Manage
     *********************/

    {
        name: 'medicinals',
        path: '/app/medicinals/manage',
        component: require('../views/panel/medicinals/manage'),
        meta: { userAuth: false }
    },

    /*
     * Create New Medicinals
     *********************/

    {
        name: 'new-medicinal',
        path: '/app/medicinals/create',
        component: require('../views/panel/medicinals/create'),
        meta: { userAuth: false }
    },
  
    /*
     * Edit To Medicinals
     *********************/

    {
        name: 'edit-medicinal',
        path: '/app/medicinals/edit/:id',
        component: require('../views/panel/medicinals/edit'),
        meta: { userAuth: false }
    },


    /*
     * Show Medicinals
     *********************/

    {
        name: 'show-medicinal',
        path: '/app/medicinals/show/:id',
        component: require('../views/panel/medicinals/show'),
        meta: { userAuth: false }
    },



    /*
     * POS
     *********************/

    {
        name: 'pos',
        path: '/app/pos',
        component: require('../views/panel/pos/manage'),
        meta: { userAuth: false }
    },


    /*
     * Get All Sales
     *********************/

    {
        name: 'sales',
        path: '/app/sales',
        component: require('../views/panel/sales/manage'),
        meta: { userAuth: false }
    },

    /*
     * Show Billing
     *********************/

    {
        name: 'show-billing',
        path: '/app/sales/billing/:id',
        component: require('../views/panel/sales/show'),
        meta: { userAuth: false }
    },


    /*
     * Categories Manage
     *********************/

    {
        name: 'categories',
        path: '/app/categories',
        component: require('../views/panel/categories/manage'),
        meta: { userAuth: false }
    },



    /*
     * Suppliers Manage
     *********************/

    {
        name: 'suppliers',
        path: '/app/suppliers',
        component: require('../views/panel/suppliers/manage'),
        meta: { userAuth: false }
    },

    /*
     * Users Manage
     *********************/

    {
        name: 'users',
        path: '/app/users',
        component: require('../views/panel/users/manage'),
        meta: { userAuth: false }
    },


    /*
     * Customer Manage
     *********************/

    {
        name: 'customers',
        path: '/app/customers',
        component: require('../views/panel/customers/manage'),
        meta: { userAuth: false }
    },

    {
        name: 'customer-show',
        path: '/app/customers/show/:id',
        component: require('../views/panel/customers/show'),
        meta: { userAuth: false }
    },

    {
        name: 'customer-pos',
        path: '/app/customers/pos/:id',
        component: require('../views/panel/customers/pos'),
        meta: { userAuth: false }
    },


    /*
     * Setting Manage
     *********************/

    {
        name: 'profile',
        path: '/app/settings/profile',
        component: require('../views/panel/settings/profile'),
        meta: { userAuth: false }
    },
    
    {
        name: 'security',
        path: '/app/settings/security',
        component: require('../views/panel/settings/security'),
        meta: { userAuth: false }
    },

        
    {
        name: 'payment-update',
        path: '/app/settings/payment-update',
        component: require('../views/panel/settings/payment-update'),
        meta: { userAuth: false }
    },


    {
        name: 'payment-billing',
        path: '/app/settings/payment-billing',
        component: require('../views/panel/settings/payment-billing'),
        meta: { userAuth: false }
    },

    {
        name: 'language',
        path: '/app/settings/language',
        component: require('../views/panel/settings/language'),
        meta: { userAuth: false }
    },


    {
        name: 'invoice',
        path: '/app/settings/invoice',
        component: require('../views/panel/settings/invoice'),
        meta: { userAuth: false }
    },
]

export default new VueRouter({
    mode: 'history',
    routes,
});