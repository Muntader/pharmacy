<template>
  <div>
   <div v-if="$auth.getUserInfo('permission') === 1">
      Subuser
   </div>
   <div v-else>
      {{$auth.getUserInfo('permission')}}
   </div>   
  </div>
</template>
<script>
export default {
  name: 'dashboard',
  created(){
  }
}
</script>
