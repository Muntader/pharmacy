<template>
  <div>

     <div class="home" 
           v-if="
           $route.name === 'home' ||
           $route.name === 'login' ||
           $route.name === 'plan' ||
           $route.name === 'signup' ||
           $route.name === 'payment' ||
           $route.name === 'forget' ||
           $route.name === 'check_hash'">  
    
    <navbar v-if="$route.name === 'home'"></navbar>
    
    <router-view class="col"></router-view>
    
    </div>
    
    
    <div class="panel" 
           v-if="
           $route.name !== 'home' &&
           $route.name !== 'login' &&
           $route.name !== 'plan' &&
           $route.name !== 'signup' &&
           $route.name !== 'payment' &&
           $route.name !== 'forget' &&
           $route.name !== 'check_hash'">
    
    <div class="row">
    
    <sidebar-panel class="col-12 col-sm-3 col-lg-2" v-if="$route.name !== 'home'"></sidebar-panel>
    
    <router-view class="col col-sm-9 col-lg-10 mt-5 p-4 mt-sm-0 mt-lg-0 "></router-view>
    
    </div>
    
    </div>

  </div>
</template>
<script>
import navbar  from "./nav.vue";
import sidebar from "./panel/sidebar.vue";
import swal from 'sweetalert';

export default {
  name: "app",
  components: {
    navbar,
    'sidebar-panel': sidebar
  },

  mounted() {
    //  [App.vue specific] When App.vue is finish loading finish the progress bar
    this.$Progress.finish();

  },
  created() {
    //  [App.vue specific] When App.vue is first loaded start the progress bar
    this.$Progress.start();
    //  hook the progress bar to start before we move router-view
    this.$router.beforeEach((to, from, next) => {
      //  does the page we want to go to have a meta.progress object
      if (to.meta.progress !== undefined) {
        let meta = to.meta.progress;
        // parse meta tags
        this.$Progress.parseMeta(meta);
      }
      //  start the progress bar
      this.$Progress.start();
      //  continue to next page
      next();
    });
    //  hook the progress bar to finish after we've finished moving router-view
    this.$router.afterEach((to, from) => {
      //  finish the progress bar
      this.$Progress.finish();
    });
  }
};
</script>
