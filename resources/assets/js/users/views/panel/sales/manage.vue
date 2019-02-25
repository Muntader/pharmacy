<template>
    <div>
        <transition name="fade">
            <div class="col-12 sales">

                <div class="text-center spinner" v-if="loading">
                    <clip-loader :loading="loading" :color="'#444'"></clip-loader>
                </div>
                <div v-if="items !== null">


                    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="text-center spinner" v-if="export_modal">
                                    <clip-loader :loading="export_modal" :color="'#444'"></clip-loader>
                                </div>
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exportModalLabel">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <a :href="export_file" download>Download file</a>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- End Export Modal -->



                    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="text-center spinner" v-if="billing_modal">
                                    <clip-loader :loading="billing_modal" :color="'#444'"></clip-loader>
                                </div>
                                <div class="modal-header">
                                <h5 class="modal-title" id="showModalLabel">Billing: {{item.billing_number}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                  <invoice1 :total="item.total_price" :order="item.customer_orders" :cname="item.customer_name" :cphone="item.customer_phone"></invoice1>
                                </div>
                                <div class="modal-footer">
                               
                                <button type="button" class="btn btn-primary" @click="PRINT_INVOICE">Print</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                               
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- End Show Modal -->


                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="text-center spinner" v-if="billing_modal">
                                    <clip-loader :loading="billing_modal" :color="'#444'"></clip-loader>
                                </div>
                                <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Billing: {{item.billing_number}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">


                                    <div class="col-12">
                                        <div class="float-left">
                                            <h4>Billing To</h4>
                                            <ul class="list-unstyled">
                                                <li>
                                                    <b>Name:</b>
                                                    <input type="text" class="form-control" v-model="item.customer_name">
                                                </li>
                                                <li>
                                                    <b>Phone:</b>
                                                    <input type="text" class="form-control" v-model="item.customer_phone">
                                                </li>
                                            </ul>
                                        </div>

                                        <table class="table">

                                            <thead>
                                                <th>{{$t('panel.medicinals.brandName')}}</th>
                                                <th>{{$t('panel.medicinals.quantity')}}</th>
                                                <th>{{$t('panel.medicinals.discount')}}</th>
                                                <th>{{$t('panel.medicinals.price')}}</th>
                                            </thead>

                                            <tbody>
                                                <tr v-for="(item, index) in item.customer_orders" :key="index" @click="show_input = index" >


                                                    <th v-if="show_input !== index">{{item.name}}</th>
                                                    <th v-if="show_input === index">
                                                        <input type="text" class="form-control" v-model="item.name">
                                                    </th>


                                                    <th v-if="show_input !== index">{{item.quantity}}</th>
                                                    <th v-if="show_input === index">
                                                        <input type="text" class="form-control" v-model="item.quantity">
                                                    </th>


                                                    <th v-if="show_input !== index">{{item.discount}}%</th>
                                                    <th v-if="show_input === index">
                                                        <input type="text" class="form-control" v-model="item.discount">
                                                    </th>


                                                    <th v-if="show_input !== index">{{item.price}}</th>
                                                    <th v-if="show_input === index">
                                                        <input type="text" class="form-control" v-model="item.price">
                                                    </th>
                                           
                                                    <th>
                                                        <button class="btn btn-sm btn-danger" @click="DELETE_FROM_CUSTOMER_ORDERS(index)">Delete</button>
                                                    </th>

                                                </tr>
                                            </tbody>

                                        </table>

                                        <b>Total price: {{item.total_price}}</b>

                                    </div>


                                </div>
                                <div class="modal-footer">
                                <button type="button" @click="UPDATE_BILLING">Update</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="col-12">

                        <div class="btn-group col-12 col-sm-6 col-lg-8 mt-1">

                            <button class="btn btn-sm btn-outline-primary ml-1" type="button" data-toggle="modal" data-target="#exportModal" @click="EXPORT_FILE">
                                <i class="fa fa-external-link" aria-hidden="true"></i> Export</button>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2 mt-1 float-right">
                            <div class="search">
                                <input type="text" class="form-control" placeholder="Search" v-model="query">
                            </div>
                        </div>

                    </div>


                    <!-- END Options Panel -->


                    <div class="col-12" v-if="search_items === null && search_items !== 'empty'">

                        <table class="table">

                            <thead>
                                <th>{{$t('panel.sales.billingNumber')}}</th>
                                <th>{{$t('panel.sales.customerName')}}</th>
                                <th>{{$t('panel.sales.customerPhone')}}</th>
                                <th>{{$t('panel.sales.totalPrice')}}</th>
                                <th>{{$t('panel.sales.billingDate')}}</th>
                                <th>{{$t('panel.sales.control')}}</th>
                            </thead>

                            <tbody>

                                <tr v-for="(item,index) in items.data" :key="index">
                                    <th>{{item.billing_number}}</th>
                                    <th>{{item.customer_name}}</th>
                                    <th>{{item.customer_phone}}</th>
                                    <th>{{item.total_price}}</th>
                                    <th>{{item.updated_at}}</th>
                                    <th>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-danger ml-1">Delete</button>
                                            <button type="button" class="btn btn-sm btn-outline-primary ml-1" data-toggle="modal" data-target="#editModal" @click="SHOW_BILLING(item.billing_number)">Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-primary ml-1" data-toggle="modal" data-target="#showModal" @click="SHOW_BILLING(item.billing_number)">Show</button>
                                        </div>
                                    </th>
                                </tr>

                            </tbody>

                        </table>

                        <div class="paginate ml-3">
                            <ul class="pagination">
                                <li class="page-item" :class="{disabled: items.first_page_url == '1' }">
                                    <a class="page-link" @click="PAGINATE(items.first_page_url)">Begin</a>
                                </li>
                                <li class="page-item" :class="{disabled: items.prev_page_url === null }">
                                    <a class="page-link" @click="PAGINATE(items.prev_page_url)">Previous</a>
                                </li>
                                <li class="page-item" :class="{disabled: items.next_page_url === null }">
                                    <a class="page-link" @click="PAGINATE(items.next_page_url)">Next</a>
                                </li>
                                <li class="page-item" :class="{disabled: items.last_page_url == '1' }">
                                    <a class="page-link" @click="PAGINATE(items.last_page_url)">End</a>
                                </li>
                            </ul>
                        </div>

                    </div>

                    <!-- END Default Table -->



                    <div class="col-12" v-if="search_items !== null && search_items !== 'empty' ">

                        <table class="table">


                            <thead>
                                <th>{{$t('panel.sales.billingNumber')}}</th>
                                <th>{{$t('panel.sales.customerName')}}</th>
                                <th>{{$t('panel.sales.customerPhone')}}</th>
                                <th>{{$t('panel.sales.totalPrice')}}</th>
                                <th>{{$t('panel.sales.billingDate')}}</th>
                                <th>{{$t('panel.sales.control')}}</th>
                            </thead>

                            <tbody>

                                <tr v-for="(item,index) in search_items.data" :key="index">
                                    <th>{{item.billing_number}}</th>
                                    <th>{{item.customer_name}}</th>
                                    <th>{{item.customer_phone}}</th>
                                    <th>{{item.total_price}}</th>
                                    <th>{{item.updated_at}}</th>
                                    <th>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-danger ml-1">Delete</button>
                                            <button type="button" class="btn btn-sm btn-outline-primary ml-1"  data-toggle="modal" data-target="#editModal" @click="SHOW_BILLING(item.billing_number)">Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-primary ml-1" data-toggle="modal" data-target="#showModal" @click="SHOW_BILLING(item.billing_number)">Show</button>
                                        </div>
                                    </th>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>


                <!-- END Search Table -->


                <div v-else>

                    <div class="text-center mt-5">
                        <h1>
                            <i class="fa fa-frown-o fa-1x" aria-hidden="true"></i> No result found</h1>
                    </div>

                </div>

                <div v-if="search_items === 'empty'">

                    <div class="text-center mt-5">
                        <h1>
                            <i class="fa fa-frown-o fa-1x" aria-hidden="true"></i> No result found</h1>
                    </div>

                </div>


                <!-- END Empty Message -->

            </div>
        </transition>

    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import ClipLoader from 'vue-spinner/src/ClipLoader.vue'
    import moment from 'moment';
    import printJS from 'print-js';
    import invoice1 from '../invoice/invoice1.vue';
    export default {
        name: 'sales',
        data() {
            return {
                query: null,
                sort: null,
                show_input: false,
            }
        },
        components: {
            ClipLoader,
            invoice1
        },
        computed: mapState({
            loading: state => state.sales.loading,
            items: state => state.sales.items,
            item: state => state.sales.item,
            billing_modal: state => state.sales.billing_modal,
            export_file: state => state.sales.export_file,
            export_modal: state => state.sales.export_modal,
            search_items: state => state.sales.search_items,
        }),
        watch: {
            query(val) {
                if (val !== null && val.length !== 0) {
                    this.$store.dispatch('SEARCH_SALES', this.query);
                } else {
                    this.query = null;
                    this.$store.commit('EMPITY_SEARCH_SALES');
                }
            },
            item: {
                handler(val) {
                    if (typeof this.item.customer_orders === 'string' || this.item.customer_orders instanceof String) { 
                      this.item.customer_orders = JSON.parse(this.item.customer_orders);
                    }else{
                      this.CALC_TOTAL_PRICE();
                    }
                },
                deep: true
            },
        },
        mounted() {
            this.$store.dispatch('GET_ALL_SALES');
        },

        methods: {

            PAGINATE(link) {
                this.$store.dispatch('GET_ALL_SALES_PAGINATE', link);
            },

            CALC_TOTAL_PRICE() {

                let total = 0;
           
                for (var x = 0; x < this.item.customer_orders.length; x++) {
                     total += parseFloat(((this.item.customer_orders[x].price - (this.item.customer_orders[x].price / 100) * this.item.customer_orders[x].discount)) * this.item.customer_orders[x].quantity);
                }
 
                this.item.total_price = parseFloat((total.toFixed(2))); 
           
           },

            DELETE_FROM_CUSTOMER_ORDERS(index) {
                this.item.customer_orders.splice(index,1);
            },


            EXPORT_FILE() {
                this.$store.dispatch('EXPORT_FILE_SALES');
            },


            SORT_BY(type, category_id) {
                this.sort = type;
                if (type === 'all') {

                    this.$store.dispatch('GET_ALL_SALES');

                } else {

                    this.$store.dispatch('GET_SORTBY_SALES', {
                        type,
                        category_id
                    });

                }
            },


            SHOW_BILLING(id){
                this.$store.dispatch('GET_BILLING',id);
            },


            UPDATE_BILLING(){
                this.$store.dispatch('UPDATE_BILLING',this.item);
            },           


            PRINT_INVOICE() {
           
                printJS({
                    printable: 'invoice-box',
                    type: 'html'
                });
                

            }
        }
    }
</script>