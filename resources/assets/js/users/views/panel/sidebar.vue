<template>
  <div>
 <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light d-sm-none">
  <a class="navbar-brand" href="#">{{$t('app_name')}}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <router-link class="btn btn-outline-primary my-2 ml-1 my-sm-0" rule="button" :to="{name: 'login'}"> <i class="fa fa-user"></i> Login</router-link>
    </form>
  </div>
</nav>

 <div class="col-sm-3 col-lg-2 d-none d-sm-block sidebar">
   <div class="p-2">
     <div class="sidebar-profile">

        <div class="image-circle">
            <img :src="'/upload/users_image/'+$auth.getUserInfo('image')" :alt="$auth.getUserInfo('username')">
        </div>
    
        <div class="text-center">
          <p class="mb-0">{{$auth.getUserInfo('username')}} </p>
       
          <span  v-if="$auth.getUserInfo('permission') === 'admin'" class="badge badge-success">Active</span>
       
          <span  v-if="$auth.getUserInfo('permission') == 1" class="badge badge-success">Manager</span>
         
          <span  v-if="$auth.getUserInfo('permission') == 2" class="badge badge-success">Cashier</span>

        </div>
       </div>
     <hr>
    <ul class="list-unstyled">
      <li>
        <router-link :to="{name: 'app'}"><b><i class="fa fa-tachometer p-1" aria-hidden="true"></i>{{$t('panel.sidebar.dashboard')}}</b></router-link>
      </li>
       <li>
        <router-link :to="{name: 'pos'}"><b><i class="fa fa-cart-plus p-1" aria-hidden="true"></i>{{$t('panel.sidebar.pos')}}</b></router-link>
      </li>
      <li>
        <router-link :to="{name: 'medicinals'}"><b><i class="fa fa-medkit p-1" aria-hidden="true"></i>{{$t('panel.sidebar.medicinals')}}</b></router-link>
      </li>
       <li>
        <router-link :to="{name: 'sales'}"><b><i class="fa fa-cart-arrow-down p-1" aria-hidden="true"></i>{{$t('panel.sidebar.sales')}}</b></router-link>
      </li> 
      <li>
        <router-link :to="{name: 'categories'}"><b><i class="fa fa-list-alt p-1" aria-hidden="true"></i>{{$t('panel.sidebar.categories')}}</b></router-link>
      </li>  

      <li>
        <router-link :to="{name: 'customers'}"><b><i class="fa fa-users p-1" aria-hidden="true"></i>{{$t('panel.sidebar.customers')}}</b></router-link>
      </li>  

       <li>
        <router-link :to="{name: 'suppliers'}"><b><i class="fa fa-truck p-1" aria-hidden="true"></i>{{$t('panel.sidebar.suppliers')}}</b></router-link>
      </li>        

       <li>
        <router-link :to="{name: 'users'}"><b><i class="fa fa-user-md p-1" aria-hidden="true"></i>{{$t('panel.sidebar.users')}}</b></router-link>
      </li>  

       <li>
        <router-link :to="{name: 'profile'}"><b><i class="fa fa-sliders p-1" aria-hidden="true"></i>{{$t('panel.sidebar.settings')}}</b></router-link>
      </li>  

      </ul>

</div>
</div>

  </div>
</template>
