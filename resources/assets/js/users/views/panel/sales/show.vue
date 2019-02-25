<template>
  <div>

  </div>
</template>

<script>

import {mapState} from 'vuex'
import moment from 'moment';
import ClipLoader from 'vue-spinner/src/ClipLoader.vue'
import printJS from 'print-js';
import invoice1 from '../invoice/invoice1.vue';


export default {
  name: 'billing-show',

  components: {
      ClipLoader,
      invoice1
  },

  computed: mapState({
      loading: state => state.sales.loading,
      item: state => state.sales.item,
  }),
  

  watch: {
      item() {

      }
  }
  
}
</script>
