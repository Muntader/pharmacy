<template>
  <div>
    <transition name="fade">
        
        <div class="col-12">
            <div class="row">

            <div class="col-12">
                <router-link class="btn btn-outline-dark" role="button" :to="{name: 'medicinals'}"><i class="fa fa-angle-left" aria-hidden="true"></i> Back</router-link>
            </div>

            <hr>

            <div class="col-sm-6 col-lg-6">

                <div class="form-group">
                    <label for="brandname">Brand name</label>
                    <input type="text" class="form-control" id="brandname"  placeholder="Brand name" v-model="data.bname">
                </div>

                <div class="form-group">
                    <label for="geneticname">Genetic name</label>
                    <input type="text" class="form-control" id="geneticname" placeholder="Genetic name" v-model="data.gname">
                </div>
            
                <div class="form-group">
                    <label for="description">Description</label>
                    <vue-editor v-model="data.description" ></vue-editor>
                </div>

                 <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" class="form-control" id="country" placeholder="Country" v-model="data.country">
                </div>

                 <div class="form-group">
                    <label for="productnumber">Product Number</label>
                    <input type="text" class="form-control" id="productnumber" placeholder="Product Number" v-model="data.productNumber">
                </div>

                 <div class="form-group">
                    <label for="importerdate">Importer date</label>
                   <datepicker  :format="CUSTOM_FORMATTER_EXPIRE" name="importerdate" :bootstrapStyling="true"></datepicker>
                </div>

               <div class="form-group">
                    <label for="expiredate">Expire date</label>
                    <datepicker :format="CUSTOM_FORMATTER_IMPORTER" name="expiredate"  :bootstrapStyling="true"></datepicker>
                </div>

                <div class="form-group">
                    <label for="description">Side effect</label>
                    <vue-editor v-model="data.sideEffect"></vue-editor >
                </div>
            </div>

            <div class="col-sm-6 col-lg-6">
             
                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control" v-model="data.category" id="category">
                        <option v-for="(item,index) in categories" :key="index"  :value="item.id">{{item.name}}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control" id="quantity" placeholder="Quantity" v-model="data.quantity">
                </div>
            
                <div class="form-group">
                    <label for="saleprice">Sale price</label>
                    <input type="number" class="form-control" id="saleprice" placeholder="Sale price" v-model="data.salePrice">
                </div>

                <div class="form-group">
                    <label for="discount">Discount</label>
                    <input type="number" class="form-control" id="discount" placeholder="Discount" v-model="data.discount">
                </div>
            
               <div class="form-group">
                    <label for="suppliedname">Supplied name</label>
                    <select class="form-control" v-model="data.suppliedName" id="suppliedname">
                        <option v-for="(item,index) in suppliers" :key="index"  :value="item.id">{{item.name}}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="originalprice">Original price</label>
                    <input type="number" class="form-control" id="originalprice" placeholder="Original price" v-model="data.originalPrice">
                </div>

                <div class="form-group">
                    <label for="barcode">Barcode</label>
                    <input type="text" class="form-control" id="barcode" placeholder="Barcode" v-model="data.barcode">
                    <barcode width="3" height="70" :value="data.barcode"></barcode>
                </div>

                <div class="form-group image-input">
                    <label for="imageupload">Image upload</label>
                    <input type="file" id="imageupload" name="imageupload" v-validate="'image'" @change="READ_IMAGE">
                    <img src="" id="preview_image" width="500px">
                    <span class="text-danger">{{ errors.first('imageupload') }}</span>
                </div>

            </div> 

                <div class="col-12">
                <div class="form-group">
                     <button class="btn btn-outline-primary" @click="CREATE">Create</button>
                </div>
                </div>     
         
          </div>
        </div>
   
    </transition>        
  </div>
</template>
<script>
import { VueEditor } from 'vue2-editor';
import Datepicker from 'vuejs-datepicker';
import {mapState} from 'vuex';
import moment from 'moment';
import VueBarcode from 'vue-barcode';

export default {
  name: 'new-medicinals',
  data() {
      return {
          data: {
            bname: null,
            gname: null,
            description:null,
            country: null,
            productNumber: null,
            importerDate: new Date(),
            expireDate: new Date(),
            sideEffect:null,
            category:null,
            quantity: null,
            salePrice: null,
            discount: 0,
            suppliedName: null,
            originalPrice: null,
            barcode: null,
          }
      }
  },
   components: {
      VueEditor,
      Datepicker,
      'barcode': VueBarcode
   },

   computed: mapState({
       categories: state => state.medicinals.categories,
       suppliers: state => state.medicinals.suppliers,
   }),
   mounted(){
        this.$store.dispatch('GET_CATEGORIES');
        this.$store.dispatch('GET_SUPPLIERS');
   },

   methods: {
       CREATE(){
        const formData = new FormData();
        const image = document.getElementById("imageupload");
        formData.append('image',image.files[0]);
        formData.append('data',JSON.stringify(this.data));
        this.$validator.validate().then(result => {
       
            this.$store.dispatch('CREATE_MEDICINALS',formData);

        });
       },

    // Read image before uplaod

    READ_IMAGE() {
      var img = document.getElementById('imageupload');
      var tgt = img.target || window.event.srcElement,
        files = tgt.files;

      // FileReader support
      if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function() {
          var srcImage = document.getElementById("preview_image");
          srcImage.style.display = "block";
          srcImage.src = fr.result;
        };
        fr.readAsDataURL(files[0]);
      } else {
        // Not supported
        // fallback -- perhaps submit the input to an iframe and temporarily store
        // them on the server until the user's session ends.
      }
    },

     CUSTOM_FORMATTER_EXPIRE(date) {
      this.data.expireDate = date;
      return moment(date).format('MMMM Do YYYY');
    },
     CUSTOM_FORMATTER_IMPORTER(date) {
      this.data.importerDate = date;
      return moment(date).format('MMMM Do YYYY');
    }
   }
}
</script>
