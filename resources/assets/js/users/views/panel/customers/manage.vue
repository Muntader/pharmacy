<template>
    <div>
        <transition name="fade">
            <div class="col-12">

                <div class="text-center spinner" v-if="loading">
                    <clip-loader :loading="loading" :color="'#444'"></clip-loader>
                </div>
                <div v-if="items !== null">


                    <div class="modal fade" id="customersModal" tabindex="-1" role="dialog" aria-labelledby="customersModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="customersModalLabel">Create new customer</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <div class="form-group">
                                         <label for="name">Name</label>
                                         <input type="text" class="form-control"  v-validate="'max:40'" :class="{'input': true, 'is-invalid': errors.has('name') }" name="name" placeholder="Supplier name" v-model="customer.name">
                                         <span v-show="errors.has('name')" class="text-danger">{{ errors.first('name') }}</span>
                                    </div>


                                     <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control"  v-validate="'max:30'" :class="{'input': true, 'is-invalid': errors.has('address') }" name="address" placeholder="Supplier address" v-model="customer.address">
                                        <span v-show="errors.has('address')" class="text-danger">{{ errors.first('address') }}</span>
                                    </div>


                                     <div class="form-group">
                                         <label for="telephone">Telephone</label>
                                         <input type="text" class="form-control"  v-validate="'max:15|numeric'" :class="{'input': true, 'is-invalid': errors.has('telephone') }" name="telephone" placeholder="Supplier telephone" v-model="customer.telephone">
                                         <span v-show="errors.has('telephone')" class="text-danger">{{ errors.first('telephone') }}</span>
                                    </div>


                                     <div class="form-group">
                                         <label for="info">Info</label>                                         
                                         <textarea type="text" class="form-control"  v-validate="'max:255'" :class="{'input': true, 'is-invalid': errors.has('info') }" name="info" placeholder="Supplier fax" v-model="customer.info"></textarea>
                                         <span v-show="errors.has('info')" class="text-danger">{{ errors.first('info') }}</span>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" @click="CREATE">Create</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- End Create  Modal -->

                    <div class="col-12">

                        <div class="btn-group col-12 col-sm-8 col-lg-8 mt-1 mb-1">
                            <button class="btn btn-sm btn-outline-primary ml-1" type="button" data-toggle="modal" data-target="#customersModal"> Add New</button>
                        </div>

                        <div class="col-sm-4 col-lg-4 mb-2 mt-1 float-right">
                            <div class="search">
                                <input type="text" class="form-control" placeholder="Search" v-model="query">
                            </div>
                        </div>

                    </div>


                    <!-- END Options Panel -->


                    <div class="col-12 table-responsive" v-if="search_items === null && search_items !== 'empty'">

                        <table class="table">

                            <thead>
                                <th>{{$t('panel.customers.name')}}</th>
                                <th>{{$t('panel.customers.telephone')}}</th>
                                <th>{{$t('panel.customers.address')}}</th>
                                <th>{{$t('panel.customers.info')}}</th>
                                <th>{{$t('panel.customers.numberSales')}}</th>
                                <th>{{$t('panel.customers.control')}}</th>
                            </thead>

                            <tbody>

                                <tr v-for="(item,index) in items.data" :key="index">
                                   
                                   
                                    <th v-if="show_input !== index">{{item.customer_name}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.customer_name" >
                                    </th>
                                   
                                    <th v-if="show_input !== index">{{item.customer_address}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.customer_address" >
                                    </th>

                                    <th v-if="show_input !== index">{{item.customer_phone}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.customer_phone" >
                                    </th>
                                  
                                    <th v-if="show_input !== index">{{item.customer_info}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.customer_info" >
                                    </th>
        
                            
                                    <th v-if="show_input !== index">{{item.customer_salesnumber}}</th>
                            

                                    <th>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary"      v-if="show_input !== index"  @click="show_input = index">Edit</button>
                                            <router-link role="button" class="btn btn-sm btn-outline-primary ml-1"  v-if="show_input !== index" :to="{name: 'customer-show', params:{id: item.customer_id}}">Show</router-link>
                                            <button class="btn btn-sm btn-success ml-1"         v-if="show_input === index"  @click="UPDATE(item.customer_id, item.customer_name, item.customer_address, item.customer_phone,item.customer_info)">Update</button>
                                            <button class="btn btn-sm btn-outline-danger ml-1"  v-if="show_input === index"  @click="DELETE(item.customer_id, index)">Delete</button>
                                        </div>
                                    </th>
                                </tr>

                            </tbody>
                        </table>

                    </div>

                    <!-- END Default Table -->

                    <div class="col-12 table-responsive" v-if="search_items !== null && search_items !== 'empty'">

                        <table class="table">

                            <thead>
                                <th>{{$t('panel.customers.name')}}</th>
                                <th>{{$t('panel.customers.telephone')}}</th>
                                <th>{{$t('panel.customers.address')}}</th>
                                <th>{{$t('panel.customers.info')}}</th>
                                <th>{{$t('panel.customers.numberSales')}}</th>
                                <th>{{$t('panel.customers.control')}}</th>
                            </thead>

                            <tbody>

                                <tr v-for="(item,index) in search_items.data" :key="index">
                                   
                                   
                                    <th v-if="show_input !== index">{{item.customer_name}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.customer_name" >
                                    </th>
                                   
                                    <th v-if="show_input !== index">{{item.customer_address}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.customer_address" >
                                    </th>

                                    <th v-if="show_input !== index">{{item.customer_phone}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.customer_phone" >
                                    </th>
                                  
                                    <th v-if="show_input !== index">{{item.customer_info}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.customer_info" >
                                    </th>
        
                            
                                    <th v-if="show_input !== index">{{item.customer_salesnumber}}</th>
                            

                                    <th>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary"      v-if="show_input !== index"  @click="show_input = index">Edit</button>
                                            <router-link role="button" class="btn btn-sm btn-outline-primary ml-1"  v-if="show_input !== index" :to="{name: 'customer-show', params:{id: item.customer_id}}">Show</router-link>
                                            <button class="btn btn-sm btn-success ml-1"         v-if="show_input === index"  @click="UPDATE(item.customer_id, item.customer_name, item.customer_address, item.customer_phone,item.customer_info)">Update</button>
                                            <button class="btn btn-sm btn-outline-danger ml-1"  v-if="show_input === index"  @click="DELETE(item.customer_id, index)">Delete</button>
                                        </div>
                                    </th>
                                </tr>

                            </tbody>
                        </table>

                    </div>

                  <div v-if="search_items === 'empty'">

                    <div class="text-center mt-5">
                        <h1><i class="fa fa-frown-o fa-1x" aria-hidden="true"></i> No result found</h1>
                    </div>

                  </div>

                </div>


                <!-- END Search Table -->


                <div v-else>

                    <div class="text-center mt-5">
                        <h1>
                            <i class="fa fa-frown-o fa-1x" aria-hidden="true"></i> No result found</h1>
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

    export default {
        name: 'customers',
        data() {
            return {
                customer: {
                    name: "",
                    address: "",
                    telephone: "",
                    info: "",
                },
                query:null,
                show_input: null,
            }
        },
        components: {
            ClipLoader
        },
        computed: mapState({
            loading: state => state.customers.loading,
            items: state => state.customers.items,
            search_items: state => state.customers.search_items,
        }),

        watch: {
            query(val){
            if(val !== null && val.length !== 0){
                this.$store.dispatch('SEARCH_CUSTOMERS',this.query);
                }else{
                this.query = null;
                this.$store.commit('EMPITY_SEARCH_CUSTOMERS');
                }
            }
        },

        mounted() {
            this.$store.dispatch('GET_ALL_CUSTOMERS');
        },

        methods: {

            CREATE() {
               this.$store.dispatch('CREATE_CUSTOMER', {customer:this.customer}); 
            },

            DELETE(customer_id,index) {
               this.show_input = false;
               this.$store.dispatch('DELETE_CUSTOMER', {customer_id,index}); 
            },

            UPDATE(customer_id,name,address,telephone,info) {
               this.show_input = false;
               this.$store.dispatch('UPDATE_CUSTOMER', {customer_id,name,address,telephone,info}); 
            }
        }
    }
</script>