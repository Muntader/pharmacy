<template>
    <div>
        <transition name="fade">
            <div class="col-12">

                <div class="text-center spinner" v-if="loading">
                    <clip-loader :loading="loading" :color="'#444'"></clip-loader>
                </div>
                <div v-if="items !== null">


                    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="categoryModalLabel">Create new user</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <div class="form-group">
                                       <label for="email">Name</label>
                                        <input type="text" class="form-control"  v-validate="'max:40|required'" :class="{'input': true, 'is-invalid': errors.has('name') }" name="name" placeholder="User name" v-model="subuser.name">
                                         <span v-show="errors.has('name')" class="text-danger">{{ errors.first('name') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">E-mail</label>
                                        <input type="text" class="form-control"  v-validate="'max:80|email|required'" :class="{'input': true, 'is-invalid': errors.has('email') }" name="email" placeholder="User email" v-model="subuser.email">
                                         <span v-show="errors.has('email')" class="text-danger">{{ errors.first('email') }}</span>
                                    </div>

                                     <div class="form-group">
                                       <label for="permission">Permission</label><br>
                                        <select class="custom-select" :class="{'input': true, 'is-invalid': errors.has('email') }" name="permission" v-validate="'max:1|required'" v-model="subuser.permission">
                                            <option value="1">Manager</option>
                                            <option value="2">Cashier</option>
                                        </select>
                                        <span v-show="errors.has('permission')" class="text-danger">{{ errors.first('permission') }}</span>
                                    </div>


                                     <div class="form-group">
                                         <label for="status">Status</label><br>
                                        <select class="custom-select" :class="{'input': true, 'is-invalid': errors.has('status') }" name="status" v-validate="'max:1|required'" v-model="subuser.status">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <span v-show="errors.has('status')" class="text-danger">{{ errors.first('status') }}</span>
                                   
                                    </div>


                                     <div class="form-group">
                                       <label for="password">Password</label>
                                        <input type="text" class="form-control"  v-validate="'max:15|required'" :class="{'input': true, 'is-invalid': errors.has('password') }" name="password" placeholder="User password" v-model="subuser.password">
                                         <span v-show="errors.has('password')" class="text-danger">{{ errors.first('password') }}</span>
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


                    <div class="col-12 table-responsive">

                        <table class="table">

                            <thead>
                                <th v-show="show_input === false">{{$t('panel.users.image')}}</th>
                                <th>{{$t('panel.users.name')}}</th>
                                <th>{{$t('panel.users.email')}}</th>
                                <th>{{$t('panel.users.permission')}}</th>
                                <th>{{$t('panel.users.status')}}</th>
                                <th>{{$t('panel.users.password')}}</th>
                                <th>{{$t('panel.users.control')}}</th>
                            </thead>

                            <tbody>

                                <tr v-for="(item,index) in items" :key="index">
                                   
                                   <th v-if="show_input !== index" >
                                       <img :src="'/upload/user_image/'+ item.image" :alt="item.name" class="rounded" width="30">
                                   </th>


                                    <th v-if="show_input !== index">{{item.name}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.name" >
                                    </th>

                                    <th v-if="show_input !== index">{{item.email}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.email" >
                                    </th>
                                   
                                    <th v-if="show_input !== index">

                                    <span  v-if="item.permission == 1" class="badge badge-info">Manager</span>
                                    <span  v-if="item.permission == 2"  class="badge badge-info">Cashier</span>

                                    </th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.permission" >
                                    </th>
                                    

                                    <th v-if="show_input !== index">

                                    <span  v-if="item.status == 1" class="badge badge-success">Active</span>
                                    <span  v-if="item.status == 0" class="badge badge-danger">Blocked</span>

                                    </th>

                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.status" >
                                    </th>

                                    <th v-if="show_input !== index">********</th>
                                    <th v-if="show_input === index">
                                        <input type="text" class="form-control" v-model="item.password" >
                                    </th>
          

                                    <th>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary"      v-if="show_input !== index"  @click="show_input = index">Edit</button>
                                            <button class="btn btn-sm btn-success ml-1"         v-if="show_input === index"  @click="UPDATE(item.id, item.name, item.email, item.permission, item.status, item.password)">Update</button>
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
        name: 'users',
        data() {
            return {
                subuser: {
                    name: "",
                    email: "",
                    permission: "",
                    status: "",
                    password: "",
                },
                show_input: false,
            }
        },
        components: {
            ClipLoader
        },
        computed: mapState({
            loading: state => state.users.loading,
            items: state => state.users.items,
        }),

        mounted() {
            this.$store.dispatch('GET_ALL_USERS');
        },

        methods: {

            CREATE() {
             this.$validator.validateAll().then((result) => {
                 if(result){
                    this.$store.dispatch('CREATE_USER', {subuser:this.subuser}); 
                 }
             });
            },

            DELETE(id,index) {
               this.show_input = false;
               this.$store.dispatch('DELETE_USER', {id,index}); 
            },

            UPDATE(id,name,email,permission,status,password) {
               this.show_input = false;
               this.$store.dispatch('UPDATE_USER', {id,name,email,permission,status,password}); 
            }
        }
    }
</script>