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
                                    <h5 class="modal-title" id="categoryModalLabel">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <div class="form-group">

                                        <input type="text" class="form-control"  v-validate="'required|max:25'" :class="{'input': true, 'is-invalid': errors.has('one') }" name="one" placeholder="Category name" v-model="categories.one">
                                         <span v-show="errors.has('one')" class="text-danger">{{ errors.first('one') }}</span>

                                    </div>
                                    
                         <div class="form-group">

                                        <input type="text" class="form-control"  v-validate="'max:25'" :class="{'input': true, 'is-invalid': errors.has('two') }" name="two" placeholder="Category name" v-model="categories.two">
                                         <span v-show="errors.has('two')" class="text-danger">{{ errors.first('two') }}</span>
                                    </div>


                         <div class="form-group">

                                        <input type="text" class="form-control"  v-validate="'max:25'" :class="{'input': true, 'is-invalid': errors.has('three') }" name="three" placeholder="Category name" v-model="categories.three">
                                         <span v-show="errors.has('three')" class="text-danger">{{ errors.first('three') }}</span>
                                    </div>

                         <div class="form-group">

                                        <input type="text" class="form-control"  v-validate="'max:25'" :class="{'input': true, 'is-invalid': errors.has('four') }" name="four" placeholder="Category name" v-model="categories.four">
                                         <span v-show="errors.has('four')" class="text-danger">{{ errors.first('four') }}</span>
                                    </div>

                         <div class="form-group">

                                        <input type="text" class="form-control"  v-validate="'max:25'" :class="{'input': true, 'is-invalid': errors.has('fife') }" name="fife" placeholder="Category name" v-model="categories.fife">
                                         <span v-show="errors.has('fife')" class="text-danger">{{ errors.first('fife') }}</span>
                                    </div>

                                 <div class="form-group">

                                        <input type="text" class="form-control"  v-validate="'max:25'" :class="{'input': true, 'is-invalid': errors.has('six') }" name="six" placeholder="Category name" v-model="categories.six">
                                         <span v-show="errors.has('six')" class="text-danger">{{ errors.first('six') }}</span>
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


                    <div class="col-12">

                        <table class="table">

                            <thead>
                                <th>{{$t('panel.categories.name')}}</th>
                                <th class="text-right">{{$t('panel.categories.control')}}</th>
                            </thead>

                            <tbody>

                                <tr v-for="(item,index) in items" :key="index">
                                    <th v-if="show_input !== index" @click="show_input = index">{{item.name}}</th>
                                    <th v-if="show_input === index">
                                        <input type="text" v-model="item.name" @mouseout="UPDATE(item.id,item.name)">
                                    </th>
                                    <th class="text-right">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-danger ml-1">Edit</button>
                                            <button class="btn btn-sm btn-outline-danger ml-1" @click="DELETE(item.id,index)">Delete</button>
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
        name: 'categories',
        data() {
            return {
                categories: {
                    one: "",
                    two: "",
                    three: "",
                    four: "",
                    fife: "",
                    six: "",
                },
                show_input: null,
            }
        },
        components: {
            ClipLoader
        },
        computed: mapState({
            loading: state => state.categories.loading,
            items: state => state.categories.items,
        }),

        mounted() {
            this.$store.dispatch('GET_ALL_CATEGORIES');
        },

        methods: {
            PAGINATE(link) {
                this.$store.dispatch('GET_ALL_MEDICINALS_PAGINATE', link);
            },

            CREATE() {
               this.$store.dispatch('CREATE_CATEGORIES', {items:this.categories}); 
            },

            DELETE(id,index) {
               this.$store.dispatch('DELETE_CATEGORY', {id,index}); 
            },

            UPDATE(id,name) {
               this.$store.dispatch('UPDATE_CATEGORY', {id,name}); 
            }
        }
    }
</script>