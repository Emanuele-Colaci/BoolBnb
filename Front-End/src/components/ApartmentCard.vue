<script>
import axios from 'axios';
import { store } from '../store.js';
export default {
    props: {
        apartment: Object,
    },
   
    data() {
        return {
            maxCaracters: 30,
            store,
        }
    },
    methods:{
      truncateText(text) {
        if(text.length > this.maxCaracters){
          return text.substr(0, this.maxCaracters) + '...';
        }
          return text
      },
      registerView(apartmentId) {
            console.log(apartmentId)
            const data = {
                apartmentId: apartmentId
            } 
            axios.post(`${this.store.baseUrl}/api/view`, data ).then( response => {
                console.log(response)
                this.success = response.data.success;
            });
        },
    }
}
</script>
<template>
      <router-link class="text-decoration-none" @click="registerView(apartment.id)" :to="{ name: 'determinato_appartamento', params: { slug: apartment.slug } }">
        <div class="card my-router-link m-2 rounded-4 min-height main-box-shadow">
            <div
              v-if="apartment.img"
              class="card-img my_card d-flex justify-content-center"
              :style="{
                backgroundImage: `url('${this.store.baseUrl}/storage/${apartment.img}')`,
                backgroundSize: 'cover',
                backgroundPosition: 'center',
                height: '300px',
                width: '100%',
              }"
            ></div>
          <div
            v-else
            class="card-img my_card"
            style="background-image: url('https://vestnorden.com/wp-content/uploads/2018/03/house-placeholder.png'); background-size: cover; background-position: center; height: 300px; width: '100%'"
          ></div>
          <div class="card-body">
            <h5 class="card-title" v-if="apartment.title">{{ apartment.title }}</h5>
            <p class="card-text truncate-text" v-if="apartment.description">{{ truncateText(apartment.description) }}</p>
            <p class="card-text"><strong><i class="fa-solid fa-location-dot"></i> </strong> {{ apartment.address }}</p>
            <p class="card-text"><strong><i class="fa-solid fa-bed"></i></strong> {{ apartment.n_beds }}</p>
            <p class="card-text"><strong><i class="fa-solid fa-door-open"></i> </strong> {{ apartment.n_rooms }}</p>
            <span class="card-text me-1"><strong><i class="fa-solid fa-circle-info"></i></strong> </span>
            <span v-if="apartment.services && apartment.services.length > 0">
                <span v-for="(service, index) in apartment.services" :key="index">
                    {{ service.name }}{{ index < apartment.services.length - 1 ? ', ' : '' }}
                </span>
            </span>
            <span v-else>Nessun servizio disponibile</span>
            <p class="card-text my-3" v-if="apartment.distance"><strong><i class="fa-solid fa-ruler"></i> Distanza: </strong> {{ (apartment.distance / 1000).toFixed(1) }} Km</p>
          </div>
        </div>
      </router-link>
  </template>
<style lang="scss">
    .my-router-link:hover{
      scale: 1.03;
    }
</style>