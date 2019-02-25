<template>
  <div>
  <transition name="fade">
    <div class="col-12">

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

     <!-- End Export  Modal -->


    <div class="col-12">
      
      <div class="btn-group col-12 col-sm-8 col-lg-8 mt-1">
        <router-link  class="btn btn-sm btn-outline-primary ml-1" role="button" :to="{name: 'new-medicinal'}"><i class="fa fa-plus" aria-hidden="true"></i> New</router-link>
        <div class="dropdown">
          <button class="btn btn-sm btn-outline-primary dropdown-toggle ml-1" type="button" id="dropdownMenuFilter"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
            <div class="dropdown-menu" @click="$event.stopPropagation();" aria-labelledby="dropdownMenuFilter">
              <a class="dropdown-item" :class="{active:sort === 'all'}" @click="SORT_BY('all')">All</a>
              <a class="dropdown-item" :class="{active:sort === 'category'}" @click="SHOW_CATEGORY" >Category</a>

              <div v-if="sort === 'category'">

              <div class="dropdown-divider"></div>
             
              <ul class="list-unstyled ml-4">
               <li v-for="(item,index) in categories" :key="index"  @click="SORT_BY('category',item.id)">{{item.name}}</li>
             </ul>

              <div class="dropdown-divider"></div>

              </div>

              <a class="dropdown-item" :class="{active:sort === 'outstock'}" @click="SORT_BY('outstock')">Out stock</a>
              <a class="dropdown-item" :class="{active:sort === 'expire'}" @click="SORT_BY('expire')">Expire</a>
            </div>
        </div>
        
        <button class="btn btn-sm btn-outline-primary ml-1"  type="button" data-toggle="modal" data-target="#exportModal" @click="EXPORT_FILE"><i class="fa fa-external-link" aria-hidden="true"></i> Export</button>
      </div>
      
      <div class="col-sm-4 col-lg-4 mb-2 mt-1 float-right">
          <div class="search">
              <input type="text" class="form-control" placeholder="Search" v-model="query">
          </div>
      </div>
    
    </div>
  
  
   <!-- END Options Panel -->


  <div class="col-12" v-if="search_items === null && search_items !== 'empty'">

    <table class="table">

      <thead>
        <th>{{$t('panel.medicinals.brandName')}}</th>
        <th>{{$t('panel.medicinals.geneticName')}}</th>
        <th>{{$t('panel.medicinals.category')}}</th>
        <th>{{$t('panel.medicinals.price')}}</th>
        <th>{{$t('panel.medicinals.quantity')}}</th>
        <th>{{$t('panel.medicinals.discount')}}</th>
        <th>{{$t('panel.medicinals.expire')}}</th>
        <th>{{$t('panel.medicinals.control')}}</th>
      </thead>

      <tbody>

        <tr v-for="(item,index) in items.data" :key="index">
          <th>{{item.bname}}</th>
          <th>{{item.gname}}</th>
          <th>{{item.category}}</th>
          <th>{{item.price}}</th>
          <th>
           <div v-if="item.product_quantity <= 4">
              <div class="custom-alert-danger">
                  <i class="fa fa-exclamation-circle text-danger" aria-hidden="true" data-placement="left" :title="'Only remained '+item.product_quantity "></i>
              </div>
          </div>  
           <div v-if="item.product_quantity <= 10 && item.product_quantity >= 4">
              <div class="custom-alert-warning">
                  <i class="fa fa-exclamation-circle text-warning" aria-hidden="true" data-placement="left" :title="'Only remained '+item.product_quantity "></i>
              </div>
          </div>       
          <div v-if="item.product_quantity > 10">
             {{item.product_quantity}}
          </div>   
            
          </th>
          <th>{{item.product_discount}}</th>
          <th>{{item.expire_date}}</th>
          <th>
           <div class="btn-group">
            <button class="btn btn-sm btn-outline-danger ml-1">Delete</button>
            <router-link role="button" class="btn btn-sm btn-outline-primary ml-1" :to="{name: 'edit-medicinal', params:{id: item.id}}">Edit</router-link>
            <router-link role="button" class="btn btn-sm btn-outline-primary ml-1" :to="{name: 'show-medicinal', params:{id: item.id}}">Show</router-link>
          </div>
          </th>
        </tr>

      </tbody>
    </table>

      <div class="paginate ml-3">
        <ul class="pagination">
          <li class="page-item" :class="{disabled: items.first_page_url == '1' }"><a class="page-link" @click="PAGINATE(items.first_page_url)">Begin</a></li>
          <li class="page-item" :class="{disabled: items.prev_page_url === null }" ><a class="page-link" @click="PAGINATE(items.prev_page_url)">Previous</a></li>
          <li class="page-item" :class="{disabled: items.next_page_url === null }" ><a class="page-link" @click="PAGINATE(items.next_page_url)">Next</a></li>
          <li class="page-item" :class="{disabled: items.last_page_url == '1' }"    ><a class="page-link" @click="PAGINATE(items.last_page_url)">End</a></li>
        </ul>
      </div>

    </div>

  <!-- END Default Table -->



  <div class="col-12" v-if="search_items !== null && search_items !== 'empty' ">

    <table class="table">

      <thead>
        <th>{{$t('panel.medicinals.brandName')}}</th>
        <th>{{$t('panel.medicinals.geneticName')}}</th>
        <th>{{$t('panel.medicinals.category')}}</th>
        <th>{{$t('panel.medicinals.price')}}</th>
        <th>{{$t('panel.medicinals.quantity')}}</th>
        <th>{{$t('panel.medicinals.discount')}}</th>
        <th>{{$t('panel.medicinals.expire')}}</th>
        <th>{{$t('panel.medicinals.control')}}</th>
      </thead>

      <tbody>

        <tr v-for="(item,index) in search_items.data" :key="index">
          <th>{{item.bname}}</th>
          <th>{{item.gname}}</th>
          <th>{{item.category}}</th>
          <th>{{item.price}}</th>
          <th>
           <div v-if="item.product_quantity <= 4">
              <div class="custom-alert-danger">
                  <i class="fa fa-exclamation-circle text-danger" aria-hidden="true" data-placement="left" :title="'Only remained '+item.product_quantity "></i>
              </div>
          </div>  
           <div v-if="item.product_quantity <= 10 && item.product_quantity >= 4">
              <div class="custom-alert-warning">
                  <i class="fa fa-exclamation-circle text-warning" aria-hidden="true" data-placement="left" :title="'Only remained '+item.product_quantity "></i>
              </div>
          </div>       
          <div v-if="item.product_quantity > 10">
             {{item.product_quantity}}
          </div>   
            
          </th>
          <th>{{item.product_discount}}</th>
          <th>{{item.expire_date}}</th>
          <th>
           <div class="btn-group">
            <button class="btn btn-sm btn-outline-danger ml-1">Delete</button>
            <router-link role="button" class="btn btn-sm btn-outline-primary ml-1" :to="{name: 'edit-medicinal', params:{id: item.id}}">Edit</router-link>
            <router-link role="button" class="btn btn-sm btn-outline-primary ml-1" :to="{name: 'show-medicinal', params:{id: item.id}}">Show</router-link>
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
        <h1><i class="fa fa-frown-o fa-1x" aria-hidden="true"></i> No result found</h1>
    </div>

  </div>

  <div v-if="search_items === 'empty'">

    <div class="text-center mt-5">
        <h1><i class="fa fa-frown-o fa-1x" aria-hidden="true"></i> No result found</h1>
    </div>

  </div>
  <!-- END Empty Message -->
</div>
  </transition>

  </div>
</template>

<script>
import { mapState,mapActions } from 'vuex'
import ClipLoader from 'vue-spinner/src/ClipLoader.vue'

export default {
  name: 'medicinals',
  data() {
    return {
      query:null,
      sort:null,
    }
  },
  components: {
    ClipLoader
  },
  computed: mapState({
    loading: state => state.medicinals.loading,
    items: state => state.medicinals.items,
    export_file: state => state.medicinals.export_file,
    export_modal: state => state.medicinals.export_modal,
    search_items: state => state.medicinals.search_items,
    categories: state => state.medicinals.categories,
    suppliers: state => state.medicinals.supplier
  }),
  watch: {
    query(val){
      if(val !== null && val.length !== 0){
        this.$store.dispatch('SEARCH_MEDICINALS',this.query);
        }else{
          this.query = null;
          this.$store.commit('EMPITY_SEARCH_MEDICINALS');
        }
    }
  },
  mounted(){
    this.$store.dispatch('GET_ALL_MEDICINALS');
    this.$store.dispatch('GET_CATEGORIES');
    this.$store.dispatch('GET_SUPPLIERS');
  },

  methods: {
    PAGINATE(link){
        this.$store.dispatch('GET_ALL_MEDICINALS_PAGINATE',link);
    },
    EXPORT_FILE(){
        this.$store.dispatch('EXPORT_FILE_MEDICINALS');
    },
    SORT_BY(type,category_id) {
        this.sort = type;
        if(type === 'all'){

           this.$store.dispatch('GET_ALL_MEDICINALS');
   
        }else{

           this.$store.dispatch('GET_SORTBY_MEDICINALS',{type,category_id});
      
        }
    },
    SHOW_CATEGORY(){
        this.sort = 'category';
    }
  }
}
</script>

