<script>
import axios from 'axios';
import { store } from '../store.js';
import AppLoading from '../components/AppLoading.vue';
import tt from '@tomtom-international/web-sdk-maps';
export default {
    components:{
        AppLoading
    },
    data() {
        return {
            store,
            apartment: [],
            loading: false,
            name: '',
            email: '',
            content: '',
            errors: {},
            apartment_id: '',
        }
    },
    
    methods: {
        async getMap(longitude, latitude) {
    // Assicurati che il documento HTML sia completamente caricato
    const mapElement = document.getElementById('map');

    if (mapElement) {
      // L'elemento "map" è stato trovato, puoi ora creare la mappa TomTom
      const point = [longitude, latitude];

      try {
        let map = tt.map({
            key: "HmfWKAQTl23dOsGqCArlxGZ4o2Jx6Q02",
            container: 'map',
            center: point,
            zoom: 15,
        });

        map.addControl(new tt.FullscreenControl());
        map.addControl(new tt.NavigationControl(), 'top-left');
        console.log(point)
        map.on('load', () => {
            new tt.Marker().setLngLat(point).addTo(map);
        });

      } catch (error) {
        console.error('Si è verificato un errore nella richiesta al servizio di geocodifica di TomTom:', error);
      }
    } else {
      console.error('L\'elemento con id "map" non è stato trovato nel DOM.');
    }
  },

        async getSingleApartment(){
            this.store.loading = true;
            try {
                const response = await axios.get(`${store.baseUrl}/api/apartment/${this.$route.params.slug}`)
           
                this.apartment = response.data.apartment;
                this.apartment_id = this.apartment.id;
                console.log(this.apartment_id);
                
                this.getMap(this.apartment.longitude, this.apartment.latitude);

                // if (this.apartment.address) {
                //     this.getTom(this.apartment.address)
                // }
                this.store.loading = false;
            } catch (error) {
                if(this.res.data.data.success){
                    this.$router.push({name: 'NotFound'})
                }
            }
        },
       
        sendForm() {
    
            const data = {
                name: this.name,
                email: this.email,
                content: this.content,
                apartment_id: this.apartment_id
            } 
    
            this.loading = true;
    
            axios.post(`${this.store.baseUrl}/api/mail`, data ).then( response => {
    
                console.log(response)
                this.success = response.data.success;
                if(!this.success){
                    this.errors = response.data.errors;
                }
                else{
                    //PULISCO I DATI IN INPUT
                    this.name = '';
                    this.email = '';
                    this.message = '';
                    this.apartment_id = '';
    
                    this.$router.push({ name: 'thank-you' })
                }
                this.loading = false
            });
    
        },
    },
    mounted(){
        this.getSingleApartment();
    },
}
</script>
<template>
    <div class="container mb-5">

        <div v-if="apartment" class="row">
            <div class="left col-12 col-md-12 col-lg-7 px-5">
    
                <!-- cover -->
                <div>
                    <div
                    v-if="apartment.img"
                    class="main-box-shadow"
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
                </div>                
              
                <!-- images -->
                <div class="d-flex thumbnail">
                    
                    <div v-for="( elem, index ) in apartment.images" :key="index" class="d-flex me-3 mt-3">  

                        <div class="box margine content">

                            <!-- wiggle -->
                            <a :href="`#${index}`" class="wiggle">
                                <img class="d-block" v-if="elem.url.includes('images')" :src="`${store.baseUrl}/storage/${elem.url}`" :alt="apartment.title">
                                <img v-else class="d-block" :src="elem.url" :alt="apartment.title">
                            </a>

                            <!-- lightbox -->
                            <div class="lightbox short-animate" :id="index">>
                                <img class="d-block rounded p-5 mt-5" v-if="elem.url.includes('images')" :src="`${store.baseUrl}/storage/${elem.url}`"  :alt="apartment.title">
                                <img v-else class="d-block rounded p-5 mt-5 " :src="elem.url" :alt="apartment.title">
                            </div>

                            <!-- lightbox controls -->
                            <div id="lightbox-controls" class="short-animate">
                                <a id="close-lightbox" class="long-animate" href="#">
                                    Close Lightbox
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- mappa -->
                <div id='map' class=' my-5 margine'>
                </div>
            </div>

            <!-- details -->
            <div class="right col-12 col-md-12 col-lg-5 px-4">
            
                    <div class="card p-3 main-background-color main-box-shadow p-3">
    
                    <!-- title and address -->
                    <ul class="p-0">
                        <li>
                            <h2 class="border-bottom mb-3 pb-3">{{ apartment.title }}</h2>
                        </li>
                        <li>
                            <h6 class="mt-2">
                                <i class="fa-solid fa-location-dot"></i>
                                {{ apartment.address }}
                            </h6>
                        </li>
                    </ul>
    
                    <!-- info appartamento -->
                    <h4 class="mt-3 mb-2">Info Appartamento</h4>
                    <ul class="p-0 d-flex flex-column row-gap-2">
                        <li>
                            <p>{{ apartment.description }}</p>
                        </li>
                        <li>
                            <strong>Stanze letto: </strong>
                            {{ apartment.n_rooms }}
                        </li>
                        <li>
                            <strong>Bagni: </strong>
                            {{ apartment.n_beds }}
                        </li>
                        <li>
                            <strong>Metri quadri: </strong>
                            {{ apartment.mq }} mq
                        </li>
                        <li v-if="apartment.price">
                            <strong>Prezzo: </strong>
                            {{ apartment.price }} &euro;
                        </li>
                    </ul>
    
                    <!-- servizi -->
                    <div>
                        <h5 class="mt-3 mb-2"> Servizi della struttura</h5>
                        <div class="d-flex flex-wrap gap-2">
    
                            <span v-for="( elem, index ) in apartment.services" :key="index" class="p-2 mt-1 card d-inline form-box-shadow"> 
                                <i :class="`fa-solid ${ elem.icon } me-1`"></i> {{  elem.name }} 
                            </span>
                        </div>
                    </div>
            </div>

                <!-- form contatta la truttura -->
                <form class="card p-4 mt-5 mb-4 margine main-background-color main-box-shadow" @submit.prevent="sendForm()">
                    <h3 class="mb-2">Contatta la struttura</h3>

                    <!-- name -->
                    <div class="mb-3">
                        <label for="" class="form-label">Nome</label>
                        <input type="text" class="form-control form-box-shadow" id="name" placeholder="Nome" name="name" v-model="name" required>
                        <p v-for="(error, index) in errors.name" :key="index" class="text-danger">
                            {{ error }}
                        </p>
                    </div>
    
                    <!-- email -->
                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="email" class="form-control form-box-shadow" id="email" placeholder="name@example.com" name="email" v-model="email" required>
                        <p v-for="(error, index) in errors.email" :key="index" class="text-danger">
                            {{ error }}
                        </p>
                    </div>
    
                    <!-- message -->
                    <div class="mb-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Messaggio</label>
                            <textarea class="form-control form-box-shadow" id="content" rows="3" name="content" v-model="content" required></textarea>
                            <p v-for="(error, index) in errors.content" :key="index" class="text-danger">
                                {{ error }}
                            </p>
                        </div>
                    </div>

                    <!-- apartment_id -->
                    <input type="hidden" name="apartment_id" id="apartment_id" v-model="apartment_id">
    
                    <!-- button -->
                    <button type="submit" class="btn primary-colour rounded-3 ps-3 pe-3" :disabled="loading">
                        <span v-if="!loading">Invia</span>
                        <span v-else>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span role="status">Caricamento...</span>
                        </span>
                    </button>

                    <!-- confirmation modal -->
                    <div class="modal mt-5" id="confirmationModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- header -->
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        Messaggio inviato con successo!
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <!-- footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn" data-bs-dismiss="modal">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
#map{
    height: 500px;
    width: 100%;       
}
.container {
    margin-top: 100px;

    .row {

        // left
        .left {

            // over
            .thumbnail {
                overflow-x: auto;

                // box
                .box {
    
                    // wiggle
                    .wiggle {
            
                        img {
                            width: 100px;
                            height: 100px;
                            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
                            cursor: pointer;
                            border-radius: 10px;
                            object-fit: cover;
                
                            &:hover {
                                transform: translateY(-5px);
                                transition: transform 0.3s ease;
                            }
                        }
                    }

                    // lightbox
                    .lightbox {
                        position:fixed;
                        top:-100%;
                        bottom:100%;
                        left:0;
                        right:0;
                        background:rgba(0, 0, 0, 0.496);
                        z-index:501;
                        opacity:0;

                        img {
                            position:absolute;
                            margin:auto;
                            top:0;
                            left:0;
                            right:0;
                            bottom:0;
                            max-width:100%;
                            max-height:95%;
                            object-fit: contain;
                        }

                        &:target {
                            top:0%;
                            bottom:0%;
                            opacity:1;
                        }

                        &:target img {
                            max-width:100%;
                            max-height:95%;
                            box-shadow: none;
                            border-radius: 20px;
                        }

                        &:target ~ #lightbox-controls {
                            top:0px;
                        }

                        &:target ~ #lightbox-controls #close-lightbox:after {
                            width:50px;
                        }

                        &:target ~ #lightbox-controls #close-lightbox:before {
                            height:50px;
                        }
                    }
                    
                    // lightbox-controls
                    #lightbox-controls {
                        position:fixed;
                        height:70px;
                        width:70px;
                        top:-70px;
                        right:0;
                        z-index:502;
                        background:rgba(0,0,0,.1);
                    
                        #close-lightbox {
                            display:block;
                            position:absolute;
                            overflow:hidden;
                            height:50px;
                            width:50px;
                            text-indent:-5000px;
                            right:10px;
                            top:80px;
                            -webkit-transform:rotate(45deg);
                            -moz-transform:rotate(45deg);
                            -ms-transform:rotate(45deg);
                            -o-transform:rotate(45deg);
                            transform:rotate(45deg);
                            
                            &:before {
                                content:'';
                                display:block;
                                position:absolute;
                                height:0px;
                                width:3px;
                                left:24px;
                                top:0;
                                background:white;
                                border-radius:2px;
                                -webkit-transition: .5s .5s ease-in-out;
                                -moz-transition: .5s .5s ease-in-out;
                                -ms-transition: .5s .5s ease-in-out;
                                -o-transition:.5s .5s ease-in-out;
                                transition:.5s .5s ease-in-out;
                            }
                            
                            &:after {
                                content:'';
                                display:block;
                                position:absolute;
                                width:0px;
                                height:3px;
                                top:24px;
                                left:0;
                                background:white;
                                border-radius:2px;
                                -webkit-transition: .5s 1s ease-in-out;
                                -moz-transition: .5s 1s ease-in-out;
                                -ms-transition: .5s 1s ease-in-out;
                                -o-transition:.5s 1s ease-in-out;
                                transition:.5s 1s ease-in-out;
                            }
                        }
                    }
                }
            }

            // maps
            .map {
                aspect-ratio: 3/2;
                width: 100%;
                border-radius: 20px;
            }
        }

        // right
        .right {

            ul {
                list-style: none;
            }

            // form
            form {
                box-shadow: rgba(50, 50, 93, 0.20) 0px 10px 30px -20px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
            }
        }
    }
}

.margine {
    margin-top: 40px;
}


.short-animate {
    -webkit-transition:.5s ease-in-out;
    -moz-transition:.5s ease-in-out;
    -ms-transition:.5s ease-in-out;
    -o-transition:.5s ease-in-out;
    transition:.5s ease-in-out;
}

.tt-fullscreen-control button {
  background-color: #C6AB7C; /* Cambia il colore di sfondo */
  color: white; /* Cambia il colore del testo */
  border: none; /* Rimuovi i bordi se necessario */
}

/* Selettore per il controllo di navigazione */
.tt-navigation-control button {
  background-color: #C6AB7C; /* Cambia il colore di sfondo */
  color: white; /* Cambia il colore del testo */
  border: none; /* Rimuovi i bordi se necessario */
}

.long-animate {
    -webkit-transition: .5s .5s ease-in-out;
    -moz-transition: .5s .5s ease-in-out;
    -ms-transition: .5s .5s ease-in-out;
    -o-transition:.5s .5s ease-in-out;
    transition:.5s .5s ease-in-out;
}

    @media (max-width: 1024px) { 
        .container {
            .row {
                .right {
                    ul {
                        li {
                            h2 {
                                font-size: 1.8rem;
                            }

                            h6 {
                                font-size: 0.9rem;
                            }
                        }
                    }

                    h4 {
                        font-size: 1.4rem;
                    }
                }
            }
        }
    }

    @media (max-width: 768px) { 
        .container {
            .row {
                .right {
                    ul {
                        li {
                            h2 {
                                font-size: 1.4rem;
                            }

                            h6 {
                                font-size: 0.9rem;
                            }
                        }
                    }

                    h4 {
                        font-size: 1.2rem;
                    }
                }
            }
        }
    }
</style>