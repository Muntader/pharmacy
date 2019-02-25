<template>
    <div>
        <transition name="fade">
            <div class="col-12 customer-order">

                <div class="text-center spinner" v-if="loading">
                    <clip-loader :loading="loading" :color="'#444'"></clip-loader>
                </div>

                    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="text-center spinner" v-if="billing_modal">
                                    <clip-loader :loading="billing_modal" :color="'#444'"></clip-loader>
                                </div>
                        
                                <div class="modal-body">
                                    <b>The number of sales of this order</b>
                                       <div >
                                           <table class="table">
                                             <thead>
                                                <th>Invoice number</th>
                                                <th>Date of sale</th>
                                             </thead>    
                                             <tbody>
                                                 <tr v-for="(item, index) in order_item.orderSalesNumner" :key="index">
                                                     <th>{{item.customer_invoiceno}}</th>
                                                     <th>{{item.created_at}}</th>
                                                 </tr>
                                             </tbody>    
                                           </table>
                                       </div>
                                    <hr>

                                    <!--  END The number of sales of this order -->

                                    <b>Invoices</b>


                                   <div v-for="(item, index) in order_item.orderDate" :key="index">
                                      <invoice1 :total="item.customer_totalprice" :order="item.customer_orders" :cname="item.customer_name" :cphone="item.customer_phone" :invoiceno="item.customer_invoiceno" :customerid="item.customer_id"></invoice1>
                                   </div>


                                </div>
                                <div class="modal-footer">
                               
                                <button type="button" class="btn btn-primary" @click="PRINT_ONLY">Take print</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                               
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- End Show Modal -->

                    <div class="col-12">

                        <div class="btn-group col-12 col-sm-8 col-lg-8 mt-1 mb-1">
                            <router-link role="button" class="btn btn-sm btn-outline-dark ml-1" :to="{name:'customers'}"> Back</router-link>
                            <router-link role="button" class="btn btn-sm btn-outline-primary ml-1" :to="{name:'customer-pos', params:{id: $route.params.id}}" > Add new order</router-link>
                        </div>

                    </div>


                    <!-- END Options Panel -->


                    <div class="col-12 table-responsive" v-if="item !== null">

                        <table class="table">

                            <thead>
                                <th>{{$t('panel.customers.customerId')}}</th>
                                <th>{{$t('panel.customers.invoiceNumber')}}</th>
                                <th>{{$t('panel.customers.totalPrice')}}</th>
                                <th>{{$t('panel.customers.info')}}</th>
                                <th>{{$t('panel.customers.orderSaleNumber')}}</th>
                                <th>{{$t('panel.customers.createdDate')}}</th>
                                <th>{{$t('panel.customers.control')}}</th>
                            </thead>

                            <tbody>

                                <tr v-for="(item,index) in item" :key="index">
                                   
                                   
                                    <th v-if="show_input !== index">{{item.customer_id}}</th>
                                   
                                    <th v-if="show_input !== index">{{item.customer_invoiceno}}</th>

                                    <th v-if="show_input !== index">{{item.customer_totalprice}}</th>
                                  
                                    <th v-if="show_input !== index">{{item.customer_info}}</th>

                                    <th v-if="show_input !== index">{{item.order_sale_number}}</th>

                                    <th v-if="show_input !== index">{{item.created_at}}</th>
                                                        

                                    <th>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary ml-1" data-toggle="modal" data-target="#showModal" @click="SELL_THIS_ORDER(item.customer_id, item.customer_invoiceno)">Sell</button> 
                                            <button type="button" class="btn btn-sm btn-outline-primary ml-1" data-toggle="modal" data-target="#showModal" @click="SHOW_BILLING(item.customer_id, item.customer_invoiceno)">Show</button>
                                        </div>
                                    </th>
                                </tr>

                            </tbody>
                        </table>

                    </div>

                    <!-- END Default Table -->


                <div v-else>

                    <div class="text-center mt-5">
                        <h1><i class="fa fa-frown-o fa-1x" aria-hidden="true"></i> No result found</h1>
                    </div>

                </div>


            </div>
        </transition>

    </div>
</template>

<script>
    import {
        mapState,
        mapActions
    } from 'vuex'
    import ClipLoader from 'vue-spinner/src/ClipLoader.vue'
    import invoice1 from '../invoice/invoice1.vue';

    export default {
        name: 'customers',
        data() {
            return {
                show_input: null,
            }
        },
        components: {
            ClipLoader,
            invoice1
        },
        computed: mapState({
            loading: state => state.customers.loading,
            item: state => state.customers.item,
            order_item: state => state.customers.order_item,
            billing_modal: state => state.customers.billing_modal,
        }),

        watch: {
            order_item(){
                for (let index = 0; index < this.order_item.orderDate.length; index++) {
                 this.order_item.orderDate[index].customer_orders =  JSON.parse(this.order_item.orderDate[index].customer_orders);
                }
            }
        },

        mounted() {
            this.$store.dispatch('GET_CUSTOMER', this.$route.params.id);
        },

        methods: {


            SHOW_BILLING(customer_id, invoice_number){
                this.$store.dispatch('GET_CUSTOMER_BILLING',{customer_id:  customer_id, invoice_number: invoice_number});
            },



            SELL_THIS_ORDER(customer_id, invoice_number) {
                // Get Invoice Data
                
                this.$store.dispatch('GET_CUSTOMER_BILLING',{customer_id:  customer_id, invoice_number: invoice_number});

                // Print it

                this.show_invoice = true;
                setTimeout(() => {
                printJS({
                    printable: 'invoice-box',
                    type: 'html'
                });
                }, 100);

                setTimeout(() => {
                   this.show_invoice = false;
                }, 100);
          
                // Store it in database

                this.$store.dispatch('SELL_ALERADY_ORDER', {customer_id:  customer_id, invoice_number: invoice_number}); 
            },




            DELETE(customer_id,index) {
               this.show_input = false;
               this.$store.dispatch('DELETE_CUSTOMER', {customer_id,index}); 
            },



            PRINT_ONLY() {
                this.show_invoice = true;
                setTimeout(() => {
                printJS({
                    printable: 'invoice-box',
                    type: 'html'
                });
                }, 100);

                setTimeout(() => {
                   this.show_invoice = false;
                }, 100);
            }
        }
    }
</script>