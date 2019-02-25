<template>
  <div>
      <div class="col-12 medicinal-info">
        
        <router-link class="btn btn-outline-dark my-3" role="button" :to="{name: 'medicinals'}"><i class="fa fa-angle-left" aria-hidden="true"></i> Back</router-link>

          <div class="row" id="form-invoice">
              <div class="col-12 col-sm-12 col-md-6 col-lg-6 left visible-print">
                   
                   <div class="title">
                       <h3><b>{{item.bname}}</b></h3>
                       <small><b>Genetic name: </b>{{item.gname}}</small><br>
                       <small><b>Category: </b>{{item.category_name}}</small>
                   </div>

                   <div class="title mt-3">
                       <h5><b>What is {{item.bname}} ?</b></h5>
                      <div v-html="item.description"></div>
                   </div>

                  <div class="title mt-3">
                       <h5><b>Side effect</b></h5>
                      <div v-html="item.side_effect"></div>
                 </div>
                   
              </div>

              <div class="col-12 col-sm-12 col-md-6 col-lg-6 visible-print right ">

                    <div class="list mt-3">
                        <ul class="list-group">

                        <li class="list-group-item">
                            <b>Quantity</b>
                            <b class="text-muted" v-if="item.product_quantity < 10">{{item.product_quantity }}</b>
                            <div class="pull-right">
                              <div v-if="item.product_quantity <= 4">
                                    <div class="custom-alert-danger">
                                        <i class="fa fa-exclamation-circle fa-2x text-danger" aria-hidden="true" data-placement="left" :title="'Only remained '+item.product_quantity "></i>
                                    </div>
                                </div>  
                                <div v-if="item.product_quantity <= 10 && item.product_quantity >= 4">
                                    <div class="custom-alert-warning">
                                        <i class="fa fa-exclamation-circle fa-2x text-warning" aria-hidden="true" data-placement="left" :title="'Only remained '+item.product_quantity "></i>
                                    </div>
                                </div>
                                <div v-if="item.product_quantity > 10">
                                  <b>{{item.product_quantity}}</b>
                                </div> 
                            </div>
                        </li>

                        <li class="list-group-item">
                            <b>Sale price</b><small class="text-muted">  -After discount</small>
                             <div class="pull-right">
                                <div v-if="item.product_discount > 0">
                                   <b  v-tooltip.bottom-left="'Tooltip on top'">{{ item.price - ((item.price / 100) * item.product_discount)}}</b>
                                </div>        
                            </div>
                        </li>

                        <li class="list-group-item" v-if="item.importer_name !== null">
                            <b>Supplied name</b>
                            <div class="pull-right">
                            <b>{{item.importer_name}}</b>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <b>Original price</b>
                            <div class="pull-right">
                            <b>{{item.original_price}}</b>
                            </div>
                        </li>


                        <li class="list-group-item">
                            <b>Discount</b>
                            <div class="pull-right">
                            <b>{{item.product_discount}} %</b>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <b>Country of productione</b>
                            <div class="pull-right">
                            <b>{{item.country}}</b>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <b>Expire date</b>
                            <div class="pull-right">
                            <b>{{item.expire_date}}</b>
                            </div>
                        </li>


                        <li class="list-group-item">
                            <b>Importer date</b>
                            <div class="pull-right">
                            <b>{{item.importer_date }}</b>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <b>Barcode</b>
                            <div class="pull-right">
                            <barcode width="2" height="50" :value="item.product_barcode"></barcode>
                            </div>
                        </li>

                        </ul>  
                   </div>

                   <hr>

                    <div class="image">
                         <img :src="'/upload/'+ $auth.getUserInfo('username') + '/'+ item.product_image" id="preview_image" width="100%">
                    </div>

              </div>

          </div>
     </div>
  </div>
</template>
<script>
import { VueEditor } from 'vue2-editor';
import Datepicker from 'vuejs-datepicker';
import {mapState} from 'vuex';
import moment from 'moment';
import VueBarcode from 'vue-barcode';
import printJS from 'print-js';

export default {
   name: 'show-medicinals',

   components: {
      VueEditor,
      'barcode': VueBarcode
   },

   computed: mapState({
       item: state => state.medicinals.item,
       categories: state => state.medicinals.categories,
       suppliers: state => state.medicinals.suppliers,
   }),

   watch:{
       item(){
           this.item.importer_date = moment(this.item.importer_date).format('MMMM Do YYYY');
           this.item.expire_date = moment(this.item.expire_date).format('MMMM Do YYYY');
       }
   },
   mounted(){
        this.$store.dispatch('GET_MEDICINAL',this.$route.params.id);
   },

   methods: {

   }
}
</script>

