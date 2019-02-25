<template>
    <div>
        <div class="text-center spinner" v-if="loading">
            <clip-loader :loading="loading" :color="'#444'"></clip-loader>
        </div>
        
        
        
        <div class="col-12 settings">

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-2 p-sm-5 p-lg-5 profile-edit">

                <div class="form-inline">
                    <div class="col-8">
                        <small>{{$t('setting.next_plan')}}</small>
                        <h4 v-if="info.name === 'monthly'">{{info.name}} for ${{info.amount/100}}/mo</h4>
                        <h4 v-if="info.name === 'yearly'"> {{info.name}} for ${{info.amount/100}}/y</h4>
                    </div>
                 <div class="col-4 pull-right text-right">
                    <button class="btn btn-outline-dark" @click="PRINT_BILLING"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                </div>
                </div>
                <table class="table table-inverse" id="billing-table">
                    <thead>
                        <tr>
                            <th>{{$t('setting.description')}}</th>
                            <th>{{$t('setting.start_period')}}</th>
                            <th>{{$t('setting.end_period')}}</th>
                            <th>{{$t('setting.total')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,key) in info.invoices" :key="key">
                            <td>{{$t('app_name')}}rine service</td>
                            <td>{{item.start}}</td>
                            <td>{{item.end}}</td>
                            <td>${{item.total/100}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<script>
    import {mapState} from "vuex";
    import ClipLoader from 'vue-spinner/src/ClipLoader.vue';
    import printJS from 'print-js';
    export default {
        components: {
            ClipLoader
        },
        data() {
            return {
                showModelError: false,
                error: null
            };
        },
        computed: mapState({
            loading: state => state.settings.loading,
            info: state => state.settings.payment_billing,
        }),
        mounted() {
            if (this.$auth.isAuthenticated()) {
                this.$store.dispatch("GET_SETTING_BILLING_DETAILS");
            }
        },

        methods: {
            PRINT_BILLING() {
                printJS({printable: 'billing-table',type: 'html'});
            }
        }
    }
</script>