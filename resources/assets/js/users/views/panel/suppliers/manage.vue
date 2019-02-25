<template>
    <div>
        <transition name="fade">
            <div class="col-12">

                <div class="text-center spinner" v-if="loading">
                    <clip-loader :loading="loading" :color="'#444'"></clip-loader>
                </div>

                    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="categoryModalLabel">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <div class="form-group">
                                        <input type="text" class="form-control"  v-validate="'max:40'" :class="{'input': true, 'is-invalid': errors.has('name') }" name="name" placeholder="Supplier name" v-model="supplier.name">
                                         <span v-show="errors.has('name')" class="text-danger">{{ errors.first('name') }}</span>
                                    </div>


                                     <div class="form-group">
                                        <input type="text" class="form-control"  v-validate="'max:30'" :class="{'input': true, 'is-invalid': errors.has('address') }" name="address" placeholder="Supplier address" v-model="supplier.address">
                                         <span v-show="errors.has('address')" class="text-danger">{{ errors.first('address') }}</span>
                                    </div>


                                     <div class="form-group">
                                        <input type="text" class="form-control"  v-validate="'max:15|numeric'" :class="{'input': true, 'is-invalid': errors.has('telephone') }" name="telephone" placeholder="Supplier telephone" v-model="supplier.telephone">
                                         <span v-show="errors.has('telephone')" class="text-danger">{{ errors.first('telephone') }}</span>
                                    </div>


                                     <div class="form-group">
                                        <input type="text" class="form-control"  v-validate="'max:15|numeric'" :class="{'input': true, 'is-invalid': errors.has('fax') }" name="fax" placeholder="Supplier fax" v-model="supplier.fax">
                                         <span v-show="errors.has('fax')" class="text-danger">{{ errors.first('fax') }}</span>
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
                            <button class="btn btn-sm btn-outline-primary ml-1" type="button" data-toggle="modal" data-target="#categoryModal"> Add New</button>
                        </div>

                    </div>


                    <!-- END Options Panel -->
              
                <div v-if="items !== null">

                    <div class="col-12 table-responsive">

                        <table class="table">

                            <thead>
                                <th>{{$t('panel.suppliers.name')}}</th>
                                <th>{{$t('panel.suppliers.telephone')}}</th>
                                <th>{{$t('panel.suppliers.address')}}</th>
                                <th>{{$t('panel.suppliers.fax')}}</th>
                                <th>{{$t('panel.suppliers.control')}}</th>
                            </thead>

                            <tbody>

                                <tr v-for="(item,index) in items" :key="index">
                                   
                                   
                                    <th v-if="show_input !== index">{{item.name}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.name" >
                                    </th>
                                   
                                    <th v-if="show_input !== index">{{item.address}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.address" >
                                    </th>

                                    <th v-if="show_input !== index">{{item.telephone}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.telephone" >
                                    </th>
                                   
                                    <th v-if="show_input !== index">{{item.fax}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.fax" >
                                    </th>
          

                                    <th>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary"      v-if="show_input !== index"  @click="show_input = index">Edit</button>
                                            <button class="btn btn-sm btn-success ml-1"         v-if="show_input === index"  @click="UPDATE(item.id, item.name, item.address, item.telephone, item.fax)">Update</button>
                                            <button class="btn btn-sm btn-outline-danger ml-1"  v-if="show_input === index"  @click="DELETE(item.id, index)">Delete</button>
                                        </div>
                                    </th>
                                </tr>

                            </tbody>
                        </table>

                    </div>

                    <!-- END Default Table -->

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
        name: 'suppliers',
        data() {
            return {
                supplier: {
                    name: "",
                    address: "",
                    telephone: "",
                    fax: "",
                    info: "",
                },
                show_input: null,
            }
        },
        components: {
            ClipLoader
        },
        computed: mapState({
            loading: state => state.suppliers.loading,
            items: state => state.suppliers.items,
        }),

        mounted() {
            this.$store.dispatch('GET_ALL_SUPPLIERS');
        },

        methods: {

            CREATE() {
               this.$store.dispatch('CREATE_SUPPLIER', {supplier:this.supplier}); 
            },

            DELETE(id,index) {
               this.show_input = false;
               this.$store.dispatch('DELETE_SUPPLIER', {id,index}); 
            },

            UPDATE(id,name,address,telephone,fax,index) {
               this.show_input = false;
               this.$store.dispatch('UPDATE_SUPPLIER', {id,name,address,telephone,fax}); 
            }
        }
    }
</script>