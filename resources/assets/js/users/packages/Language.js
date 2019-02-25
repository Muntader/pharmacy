import Vue from "vue";
import VueI18n from "vue-i18n";

Vue.use(VueI18n);

const messages = {

/*
 * English language
 *******************/


 en: {
    app_name: 'Laurus',

    home: {
        backgroundTitle: 'Manage a pharmacy in less than 5 minutes and manage it anytime and anywhere'
    },
    panel: {

        sidebar: {
        dashboard: 'Dashboard',
        medicinals: 'Medicinals',
        sales: 'Sales',
        pos: 'POS',
        categories: 'Categories',
        suppliers: 'Suppliers',
        users: 'Users',
        customers: 'Customers',
            settings: 'Settings',

        },

        medicinals: {
            brandName:'Brand name',
            geneticName:'Genetic name',
            category:'Category',
            price:'Price',
            quantity:'Quantity',
            discount:'Discount',
            expire:'Expire',
            control: 'Control'
        },

        sales: {
            billingNumber: 'Billing Number',
            customerName: 'Customer name',
            customerPhone: 'Customer phone',
            totalPrice: 'Total price',
            billingDate: 'Billing date',
            control: 'Control'
        },
        categories: {
            name: 'Name',
            control: 'Control'
        },

        suppliers: {
            name: 'Name',
            telephone: 'Telephone',
            address: 'Address',
            fax: 'Fax',
            info: 'Info',
            control: 'Control'
        },

        customers: {
            name: 'Name',
            telephone: 'Telephone',
            address: 'Address',
            numberSales: 'Number sales',
            info: 'Info',
            control: 'Control',
            customerId: 'Customer id',
            invoiceNumber: 'Invoice number',
            totalPrice: 'Total price',
            info: 'Info',
            orderSaleNumber: 'Sale number',
            createdDate: 'Created at',
        },
        users: {
            name: 'Name',
            email: 'Email',
            permission: 'Permission',
            password: 'Password',
            image: 'Image',
            status: 'Status',
            username: 'Username',
            control: 'Control'
        },

        settings: {
            user_setting: "User Settings",
            biling: "Billing",
            app_setting: "App setting",
            language: "Language",
            profile: "Profile",
            security: "Security",
            update_payment: "Update payment",
            billing_details: "Billing details",
            change_plan: "Change plan",
            language: "Language",

            description: "Description",
            next_plan: "Next months plan",
            start_period: "Start period",
            end_period: "End period",
            total: "Total",
            update_profile_picture: "Update Profile Picture",
            name: "Name",
            mail: "E-mail",
            successful_update: "Successful update",
            update: "Update",
            password: "Password",
            password_confirm: "Password-Confirm",
            credit_card_or_debit: "Credit card or debit",
            cancel_your_memebrship: "Cancel your memebrship",
            resume_your_memebrship: "Resume your memebrship",
            cancel_memebrship: "Cancel memebrship",
            resume_memebrship: "Resume memebrship",
            cancel_note: "Cancellation will be effective at the end of your current billing",
            change_streaming_plan: "Change Streaming Plan",
            monthly: "Monthly",
            first_week_free: "First week free",
            annual: "Annual",
            mo: "mo",
            y: "y",
            m_price: "59.99",
            y_price: "15.99",
            select_language: "Select language",
            select_theme: "Select theme",
            appearance: "Appearance", //new
            light: "Light", //new
            dark: "Dark", //new
            adjust_subtitles: "Adjust captions", //new
            email_already: "Email has already been taken", // new
        },

    }
 }
}

// Create VueI18n instance with options
export default new VueI18n({
    locale: "en", // set locale
    messages, // set locale messages
})
