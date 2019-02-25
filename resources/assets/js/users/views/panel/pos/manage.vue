<template>

    <div>
        <div class="col-12 pos" v-if="!show_invoice">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-7 col-lg-7">

                    <div class="col-12 pos-control">
                        <div class="row">
                            <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                                <div class="search">
                                    <input type="text" class="form-control" placeholder="Search" v-model="query">
                                </div>
                            </div>

                            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                                <div class="dropdown">
                                    <button class="btn btn-md btn-outline-primary dropdown-toggle ml-1" type="button" id="dropdownMenuFilter" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
                                    <div class="dropdown-menu" @click="$event.stopPropagation();" aria-labelledby="dropdownMenuFilter">
                                        <a class="dropdown-item" :class="{active:sort === 'all'}" @click="SORT_BY('all')">All</a>
                                        <a class="dropdown-item" :class="{active:sort === 'category'}" @click="SHOW_CATEGORY">Category</a>

                                        <div v-if="sort === 'category'">

                                            <div class="dropdown-divider"></div>

                                            <ul class="list-unstyled ml-4">
                                                <li v-for="(item,index) in categories" :key="index" @click="SORT_BY('category',item.id)">{{item.name}}</li>
                                            </ul>

                                            <div class="dropdown-divider"></div>

                                        </div>

                                        <a class="dropdown-item" :class="{active:sort === 'outstock'}" @click="SORT_BY('outstock')">Out stock</a>
                                        <a class="dropdown-item" :class="{active:sort === 'expire'}" @click="SORT_BY('expire')">Expire</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>



                    <!-- End Search and Sorting -->



                    <div class="col-12">
                        <div class="row">

                            <div class="col-12" v-if="items !== 'empty'">
                                <div class="btn-group">
                                    <div v-for="(item, index) in items" :key="index">
                                        <div v-if="item.product_quantity !== 0">
                                             <button :class="{ 'btn btn-md btn-outline-primary ml-1 mt-1': !item.active, 'btn btn-md btn-success ml-1 mt-1': item.active }"  @click="SELECT_MEDICINAL(index)" @mouseover="ORDER_INFO(index)">{{ item.bname }}</button>
                                        </div>
                                        <div v-if="item.product_quantity === 0">
                                             <button class="btn btn-md btn-danger ml-1 mt-1" @mouseover="ORDER_INFO(index)" disabled>{{ item.bname }}</button>
                                        </div>
                                    </div>
                                </div>
                             </div>

                            <div class="col-12" v-else>
                                <div class="text-center mt-5">
                                    <h1>
                                        <i class="fa fa-frown-o fa-1x" aria-hidden="true"></i> No result found</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- END POS-CONTROL -->

                <div class="col-12  col-sm-12 col-md-5 col-lg-5 mt-2 invoice">


                    <div class="col-12 mt-2" v-if="show_order_info">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">{{order_info.bname}}</h4>
                                <h6 class="card-subtitle mb-2 text-muted">{{order_info.gname}}</h6>
                                <p class="card-text" v-html="order_info.side_effect"></p>
                                <h3>
                                    <b class="mb-2 text-success">${{order_info.price}}</b><br>
                                    <h6 class="mt-1  card-subtitle text-muted text-block">Quantity: {{order_info.product_quantity}}</h6>
                                </h3>
                                 <router-link class="btn btn-sm btn-success float-right" role="button" :to="{name: 'show-medicinal', params: {id: order_info.id}}">More info</router-link>
                            </div>
                        </div>
                    </div>


                    <!-- Medicinals info -->

                    <hr>


                    <div class="col-12">
                        <div class="float-left">
                            <h4>Billing To</h4>
                            <ul class="list-unstyled">
                                <li>
                                    <b>Name:</b>
                                    <input type="text" class="form-control" v-model="customer_name">
                                </li>
                                <li>
                                    <b>Phone:</b>
                                    <input type="text" class="form-control" v-model="customer_phone">
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
                                <tr v-for="(item, index) in order" :key="index" @click="show_input = index">


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


                                </tr>
                            </tbody>

                        </table>

                        <b>Total price: {{total_price}}</b><br>
                 
                       <button class="btn btn-md btn-primary bt-5" @click="PRINT_INVOICE">Sell</button>
                    </div>


                    <!-- END Invoice Control -->


                </div>
            </div>
        </div>

        <invoice1 v-if="show_invoice" :total="total_price" :order="order" :cname="customer_name" :cphone="customer_phone" :invoiceno="invoice_number"></invoice1>
    
    </div>

</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import {mapState} from 'vuex';
    import moment from 'moment';
    import VueBarcode from 'vue-barcode';
    import ClipLoader from 'vue-spinner/src/ClipLoader.vue'
    import printJS from 'print-js';
 
    const invoice1 =  require('../invoice/invoice1.vue');


    export default {
        name: 'medicinals',
        data() {
            return {
                query: null,
                sort: null,
                order: [],
                customer_name: "",
                customer_phone: "",
                total_price: null,
                show_invoice:false,
                show_input: false,
                show_order_info: false,
                order_info: {},
                billing_number: Math.floor(Date.now() / (Math.random() * 10000)),
            }
        },
        components: {
            ClipLoader,
            invoice1
        },
        computed: mapState({
            loading: state => state.pos.loading,
            items: state => state.pos.items,
            categories: state => state.medicinals.categories,
            invoice_number: state => state.pos.invoice_number,
        }),


        watch: {
            
            query(val) {
                if (val !== null && val.length !== 0) {
                    this.$store.dispatch('SEARCH_MEDICINALS_POS', this.query);
                } else {
                    this.query = null;
                    this.$store.dispatch('GET_ALL_MEDICINALS_POS');
                    this.$store.commit('EMPITY_SEARCH_MEDICINALS_POS');
                }
            },

            items() {
                for (var i = 0; i < this.items.length; i++) {
                    for (var x = 0; x < this.order.length; x++) {
                        if (this.items[i].id === this.order[x].id) {
                            this.$set(this.items[i], 'active', true)
                        }
                    }
                }
            },
            
            order: {
                handler(val) {
                  this.CALC_TOTAL_PRICE();
                },
                deep: true
            },

            total_price() {
                this.total_price = parseFloat((this.total_price).toFixed(2));
            },

            invoice_number(){
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
        },
        mounted() {
            this.$store.dispatch('GET_ALL_MEDICINALS_POS');
            this.$store.dispatch('GET_CATEGORIES');
        },

        methods: {

            SELECT_MEDICINAL(key) {
                if (this.items[key].active) {
                    this.$set(this.items[key], 'active', false)
                    for (var x = 0; x < this.order.length; x++) {
                        if (this.items[key].id === this.order[x].id) {
                            this.total_price = parseFloat(this.total_price - this.order[x].price);
                            this.items[key].product_quantity = parseInt(this.items[key].product_quantity + this.order[x].quantity) 
                            this.order.splice(x, 1)
                        }
                    }
                } else {
                    this.$set(this.items[key], 'active', true)
                    this.items[key].product_quantity =  parseInt(this.items[key].product_quantity - 1)
                    this.order.push({
                        id: this.items[key].id,
                        name: this.items[key].bname,
                        price: this.items[key].price - (this.items[key].price / 100) * this.items[key].product_discount,
                        quantity: 1,
                        discount: this.items[key].product_discount,
                    })
                }

            },

            CALC_TOTAL_PRICE() {
                this.total_price = 0;

                for (var x = 0; x < this.order.length; x++) {
                    this.total_price += parseFloat(((this.order[x].price - (this.order[x].price / 100) * this.order[x].discount)) * this.order[x].quantity)
                }

            },


            SORT_BY(type, category_id) {
                this.sort = type;
                if (type === 'all') {

                    this.$store.dispatch('GET_ALL_MEDICINALS_POS');

                } else {

                    this.$store.dispatch('GET_SORTBY_MEDICINALS_POS', {
                        type,
                        category_id
                    });

                }
            },


            SHOW_CATEGORY() {
                this.sort = 'category';
            },

            SHOW_INPUT(input) {
                if (input === 'name') {
                    this.show_name_input = true
                    this.show_quantity_input = false;
                    this.show_discount_input = false;
                    this.show_price_input = false;
                }
            },

            ORDER_INFO(key) {
                this.show_order_info = true;
                this.order_info = this.items[key];
            },

            PRINT_INVOICE() {
           
                this.show_invoice = true;
                this.$store.dispatch('GET_ALL_MEDICINALS_POS');

                this.$store.dispatch('CREATE_NEW_PAYMENT',{customer_name:this.customer_name, customer_address:this.customer_address, customer_phone:this.customer_phone, total_price:this.total_price, billing_number:this.billing_number, order:this.order});
            }
        }

    }
</script>