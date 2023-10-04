<script >
import AppHeader from './components/AppHeader.vue';
export default {
  components:{
      AppHeader
    },
}
</script>

<template>
  <div class="background_index">
    <AppHeader />
    <router-view class="padding-top"></router-view>
  </div>
</template>

<style lang="scss">
  @use './styles/generals.scss' as *;

  .padding-top{
    padding-top: 150px;
  }
  .background_index::before{
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url(https://d8uprjylqiycc.cloudfront.net/media/catalog/product/1/4/146_q-bic_h.jpg);
    filter: opacity(50%);
    background-repeat: no-repeat;
    background-size: cover;
    z-index: -1;  
}

</style>
