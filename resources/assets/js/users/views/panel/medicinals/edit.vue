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
                    <input type="text" class="form-control" id="brandname"  placeholder="Brand name" v-model="item.bname">
                </div>

                <div class="form-group">
                    <label for="geneticname">Genetic name</label>
                    <input type="text" class="form-control" id="geneticname" placeholder="Genetic name" v-model="item.gname">
                </div>
            
                <div class="form-group">
                    <label for="description">description</label>
                    <vue-editor id="description" v-model="item.description" ></vue-editor>
                </div>

                 <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" class="form-control" id="country" placeholder="Country" v-model="item.country">
                </div>

                 <div class="form-group">
                    <label for="productnumber">Product Number</label>
                    <input type="text" class="form-control" id="productnumber" placeholder="Product Number" v-model="item.product_number">
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
                    <label for="sideeffect">Side effect</label>
                    <vue-editor id="sideeffect" v-model="item.side_effect"></vue-editor >
                </div>
            </div>

            <div class="col-sm-6 col-lg-6">
             
                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control" v-model="item.category_id" id="category">
                        <option v-for="(item,index) in categories" :key="index"  :value="item.id">{{item.name}}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control" id="quantity" placeholder="Quantity" v-model="item.product_quantity">
                </div>
            
                <div class="form-group">
                    <label for="saleprice">Sale price</label>
                    <input type="number" class="form-control" id="saleprice" placeholder="Sale price" v-model="item.price">
                </div>

                <div class="form-group">
                    <label for="discount">Discount</label>
                    <input type="number" class="form-control" id="discount" placeholder="Discount" v-model="item.product_discount">
                </div>
            
               <div class="form-group">
                    <label for="suppliedname">Supplied name</label>
                    <select class="form-control" v-model="item.supplier_name" id="suppliedname">
                        <option v-for="(item,index) in suppliers" :key="index"  :value="item.id">{{item.name}}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="originalprice">Original price</label>
                    <input type="number" class="form-control" id="originalprice" placeholder="Original price" v-model="item.original_price">
                </div>

                <div class="form-group">
                    <label for="barcode">Barcode</label>
                    <input type="text" class="form-control" id="barcode" placeholder="Barcode" v-model="item.product_barcode">
                    <barcode format="CODE128" width="3" height="70" :value="item.product_barcode"></barcode>
                </div>

                <div class="form-group image-input">
                    <label for="imageupload">Image upload</label>
                    <input type="file" id="imageupload" name="imageupload" v-validate="'image'" @change="READ_IMAGE">
                    <img :src="'/upload/'+ $auth.getUserInfo('username') + '/'+ item.product_image" id="preview_image" width="500px">
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
  name: 'edit-medicinals',
  data() {
      return {

      }
  },
   components: {
      VueEditor,
      Datepicker,
      'barcode': VueBarcode
   },

   computed: mapState({
       item: state => state.medicinals.item,
       categories: state => state.medicinals.categories,
       suppliers: state => state.medicinals.suppliers,
   }),
   mounted(){
        this.$store.dispatch('GET_CATEGORIES');
        this.$store.dispatch('GET_SUPPLIERS');
        this.$store.dispatch('GET_MEDICINAL',this.$route.params.id);
   },

   methods: {
       CREATE(){
        const formData = new FormData();
        const image = document.getElementById("imageupload");
        formData.append('image',image.files[0]);
        formData.append('data',JSON.stringify(this.item));

        this.$validator.validate().then(result => {
    
            this.$store.dispatch('UPDATE_MEDICINAL',formData);

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
      this.item.expire_date = date;
      return moment(date).format('MMMM Do YYYY');
    },
     CUSTOM_FORMATTER_IMPORTER(date) {
      this.item.importer_date = date;
      return moment(date).format('MMMM Do YYYY');
    }
   }
}
</script>
