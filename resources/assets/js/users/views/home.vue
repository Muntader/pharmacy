<template>
  <div>
    <div class="container-fluid">
      <div class="background">
        <img src="/img/girl-background.jpg" alt="background">
        <div class="title">
          <h1>{{$t('home.backgroundTitle')}}</h1>
          <router-link class="btn btn-lg btn-light" :to="{name: 'app'}" v-if=" $auth.isAuthenticated()"> 
            <div class="image-circle">
            <img :src="'/img/'+$auth.getUserInfo('image')" :alt="$auth.getUserInfo('username')" width="70px">
            </div>
             {{$auth.getUserInfo('username')}}
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'home'
}
</script>
