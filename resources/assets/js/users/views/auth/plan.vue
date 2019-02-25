<template>
  <div>
      <div class="col-12 col-lg-8 col-sm-8 offset-lg-2 offset-sm-2 mt-4 p-3 plan">
          <div class="row">

          <div class="col-12 col-lg-6 col-sm-6 p-3">
              <div class="plan-card">
              <div class="price text-center">
                  <h1 class="title">Standard</h1>
                  <h1 class="amount">$20/m</h1>
                  <hr>
              </div>
              <div class="description p-lg-3 p-sm-3">
                 <ul class="list-unstyled">
                  <li>Award-winning, 24/7 support</li>   
                 <li>1 User Manange</li>
                 <li>100 Product Limit</li>
                 <li>100 Customer Limit</li>
                 </ul>
              </div>
            <div class="button-next text-center mt-5">
            <button class="btn btn-lg btn-primary" @click="NEXT(1)" >Add To Cart</button>
            </div>
          </div>
         </div>

          <div class="col-12 col-lg-6 col-sm-6 p-3">
              <div class="plan-card">
              <div class="price text-center">
                  <h1 class="title">Premium</h1>
                  <h1 class="amount">$30/m</h1>
                  <hr>
              </div>
              <div class="description p-lg-3 p-sm-3">
                 <ul class="list-unstyled">
                  <li>Award-winning, 24/7 support</li>   
                 <li>5 User Manange</li>
                 <li>500 Product Limit</li>
                 <li>500 Customer Limit</li>
                 </ul>
              </div>
             <div class="button-next text-center mt-5">
            <button class="btn btn-lg btn-primary" @click="NEXT(2)" >Add To Cart</button>
            </div>
          </div>  
          </div>  
        </div>
        </div>
  </div>
</template>

<script>
export default {
  name: 'plan',
  data() {
      return {
          plan_id: null,
      }
  },
  mounted(){
      if(localStorage.getItem('_plan') == '1' || localStorage.getItem('_plan') == '2'){
          localStorage.removeItem('_plan');
      }
  },

  methods: {
      NEXT(id){
          localStorage.setItem('_plan',id);
          this.$router.push({name: 'signup'})
      }
  }

}
</script>
