<script>
import axios from 'axios';
import { store } from '../store.js';
import AppLoading from '../components/AppLoading.vue';
import ApartmentCard from '../components/ApartmentCard.vue';
import { autoAddress } from '../address.js';
  
export default {
    components: {
        AppLoading,
        ApartmentCard,
    },
    data() {
        return {
            apartmentsFilter: [],
            store,
            autoAddress,
            myUrl: '',
            n_rooms: null,
            n_beds: null,
            lat: null,
            lon: null,
            range: 20,
            services: [],
            selectedServices: [],
            sliderPosition: 0,
            itemsToShow: 4,
            sponsoredApartmentsArray: [],
            nonSponsoredApartmentsArray: []
        };
    },
    computed: {
        visibleServices(){
            const start = this.sliderPosition;
            const end = start + this.itemsToShow;
            return this.services.slice(start, end);
        },
    },
    created() {
        this.getApartments();
        this.getServices();
        this.filterApartments();
        this.calculateItemsToShow(); // Chiamato all'inizio
        this.handleWindowResize(); // Aggiungi un ascoltatore per il ridimensionamento
    },
    methods: {
        sponsoredApartments(){
            this.sponsoredApartmentsArray= [];
            const dateNow = new Date();
            dateNow.setHours(dateNow.getHours() + 2);
            const formattedDate = dateNow.toISOString().slice(0, 19).replace("T", " ");
            this.apartmentsFilter.forEach(apartment => {
                if (
                    apartment.sponsorships &&
                    apartment.sponsorships.length > 0 &&
                    apartment.visible
                ) {
                    apartment.sponsorships.forEach(sponsorship => {
                    if (new Date(formattedDate) < new Date(sponsorship.pivot.end_date)) {
                        // Aggiungi l'appartamento alla lista degli appartamenti sponsorizzati
                        this.sponsoredApartmentsArray.push(apartment);
                    }
                    });
                }
                });
        },  
        nonSponsoredApartments() {
            this.nonSponsoredApartmentsArray = [];
            const apartmentsMap = {}; // Creare un oggetto mappa per tenere traccia degli appartamenti

            const dateNow = new Date();
            dateNow.setHours(dateNow.getHours() + 2);
            const formattedDate = dateNow.toISOString().slice(0, 19).replace("T", " ");

            // Filtra gli appartamenti non sponsorizzati e visibili
            const nonSponsoredApartments = this.apartmentsFilter.filter(apartment => (
                (!apartment.sponsorships || apartment.sponsorships.length === 0) && apartment.visible
            ));

            // Aggiungi gli appartamenti non sponsorizzati all'array e alla mappa
            nonSponsoredApartments.forEach(apartment => {
                this.nonSponsoredApartmentsArray.push(apartment);
                apartmentsMap[apartment.id] = true; // Aggiungi l'appartamento alla mappa
            });

            // Filtra gli appartamenti sponsorizzati scaduti
            this.apartmentsFilter.forEach(apartment => {
                if (apartment.sponsorships && apartment.sponsorships.length > 0 && apartment.visible) {
                    const hasValidSponsorship = apartment.sponsorships.some(sponsorship => {
                        return new Date(formattedDate) < new Date(sponsorship.pivot.end_date);
                    });

                    if (!hasValidSponsorship && !apartmentsMap[apartment.id]) {
                        this.nonSponsoredApartmentsArray.push(apartment);
                        apartmentsMap[apartment.id] = true; // Aggiungi l'appartamento alla mappa
                    }
                }
            });
        },
        prevSlide(){
            if(this.sliderPosition > 0){
                this.sliderPosition -= this.itemsToShow; // Aggiorna il valore del passo
            }
        },

        nextSlide(){
            const maxPosition = this.services.length - this.itemsToShow;
            if(this.sliderPosition < maxPosition){
                this.sliderPosition += this.itemsToShow; // Aggiorna il valore del passo
            }
        },

        moveSlide(direction) {
            if(direction === 'prev'){
                this.prevSlide();
            }else if(direction === 'next'){
                this.nextSlide();
            }
        },

        getApartments(){
            this.store.loading = true;
            axios.get(`${this.store.baseUrl}/api/apartmentsFilter`).then((response) => {
                if(response.data.success){
                    this.apartmentsFilter = response.data.results;
                    this.store.loading = false;
                } else{
                    this.$router.push({ name: 'not-found' });
                }
            });
        },

        resetFilters(){
            this.n_rooms = null;
            this.n_beds = null;
            store.address = null;
            this.range = 20;
            this.lat = null;
            this.lon = null;
            // this.getApartments();
            this.selectedServices = [];
            // this.filterApartments();
            this.sponsoredApartmentsArray= [];
            this.nonSponsoredApartmentsArray= []
            this.$router.push({ name: 'search', address:'' });
        },

        filterApartments(){
            const query = {
                n_rooms: this.n_rooms,
                n_beds: this.n_beds,
                address: this.store.address,
                range: this.range,
                services: this.selectedServices.join(','),
            };
            this.apartmentsFilter = [];

            axios
            .get(
            `https://api.tomtom.com/search/2/geocode/${this.store.address}.json?key=i0LOdzaKgh77G9KYA4lqDP3GOttQ0kZT`
            )
            .then((response) => {

            // Estrai latitudine e longitudine dalla risposta
            this.lat = response.data.results[0].position.lat;
            this.lon = response.data.results[0].position.lon;

            // Ora hai le coordinate latitudine e longitudine, puoi usarle per costruire l'URL
            this.myUrl = `${this.store.baseUrl}/api/apartmentsFilter?`;

            if(this.n_rooms !== null){
                this.myUrl += `n_rooms=${this.n_rooms}&`;
            }

            if(this.n_beds !== null){
                this.myUrl += `n_beds=${this.n_beds}&`;
            }

            if(this.store.address !== null){
                this.myUrl += `address=${this.store.address}&`;
                if (this.range !== null && this.lat !== null && this.lon !== null) {
                this.myUrl += `range=${this.range}&lat=${this.lat}&lon=${this.lon}&`;
                }
            }

            if(this.selectedServices.length > 0){
                const encodedServices = this.selectedServices.map((service) => encodeURIComponent(service));
                this.myUrl += `services=${encodedServices.join(',')}&`;
            }

            axios.get(this.myUrl).then((response) => {
                this.apartmentsFilter = response.data.results;
                this.sponsoredApartments()
                this.nonSponsoredApartments();
            });

                this.$router.push({ name: 'search', query });

            })
            .catch((error) => {
                console.error('Errore nella geocodifica dell\'indirizzo:', error);
            });

           
        },

        getServices(){
            axios.get(`${this.store.baseUrl}/api/services`).then((response) => {
                if(response.data.success){
                    this.services = response.data.results;
                }else{
                    this.$router.push({ name: 'not-found' });
                }
            });
        },

        calculateItemsToShow(){
            const screenWidth = window.innerWidth;

            if(screenWidth >= 1200){
                this.itemsToShow = 5;
            }else if(screenWidth >= 992){
                this.itemsToShow = 5;
            }else if(screenWidth >= 768){
                this.itemsToShow = 4;
            }else{
                this.itemsToShow = 2;
            }
        },

        handleWindowResize(){
            window.addEventListener('resize', this.calculateItemsToShow);
        },
    },
};
</script>

<template>
    <AppLoading v-if="store.loading" />
    <div v-else class="container">
        <form action="">
            <div class="col-12 d-md-flex justify-content-between align-items-center main-background-color main-box-shadow p-3">
            <div class="me-3 my-3">
                    <label for="address">Indirizzo</label>
                    <input
                        type="text"
                        id="address"
                        class="form-control me-2 rounded-3 form-box-shadow"
                        placeholder="Indirizzo"
                        @keyup="autoAddress"
                        v-model="store.address"
                    />
                    <ul id="autocomplete-list" class="list-group box-list"></ul>
                </div>
                <div class="me-3 my-3">
                    <label for="range">Raggio di ricerca</label>
                    <input
                        type="number"
                        id="range"
                        class="form-control me-2 rounded-3 form-box-shadow"
                        placeholder="Raggio di ricerca"
                        min="20"
                        v-model="range"
                    />
                </div>
                <div class="me-3 my-3">
                    <label>Numero di stanze</label>
                    <input
                        type="number"
                        class="form-control me-2 rounded-3 form-box-shadow"
                        placeholder="Numero di stanze"
                        v-model="n_rooms"
                    />
                </div>
                <div class="me-3 my-3">
                    <label>Posti letto</label>
                    <input
                        type="number"
                        class="form-control rounded-3 form-box-shadow"
                        placeholder="Posti letto"
                        v-model="n_beds"
                    />
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between my-4 main-background-color main-box-shadow p-3">
                <button class="slider-control" @click.prevent="moveSlide('prev')"><i class="fa-solid fa-arrow-left"></i></button>
                <div class="d-flex align-items-center justify-content-between">
                    <div
                        class="service-slider slider"
                        :style="{ transform: `translateX(${sliderPosition}%)` }"
                    >
                        <div v-for="service in visibleServices" :key="service.id" class="slider-item">
                            <div class="text-center">
                                <i :class="service.icon"></i>
                            </div>
                            <label class="my-checkbox">
                                <input
                                    :value="service.id"
                                    class="btn-check"
                                    type="checkbox"
                                    name="service"
                                    id="service"
                                    v-model="selectedServices"
                                />
                                <div class="btn btn-service" :class="{ 'active': selectedServices.includes(service.id) }">
                                    {{ service.name }}
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <button class="slider-control" @click.prevent="moveSlide('next')"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
            <div class="d-flex align-items-center justify-content-center">
                <button class="btn primary-colour rounded-3 ps-3 pe-3" @click="filterApartments" type="button">Filtra</button>
                <button class="btn primary-colour rounded-3 ps-3 pe-3 mx-3" @click="resetFilters" type="button">Reset</button>
            </div>
        </form>
       
            <div v-if="sponsoredApartmentsArray.length > 0">
                <!-- Visualizza gli appartamenti sponsorizzati -->
                <h1>Appartamenti in evidenza</h1>
                <div class="row">
                    <div v-for="apartment in sponsoredApartmentsArray" :key="apartment.id" class="col-12 col-md-6 col-lg-4 mb-4">
                        <ApartmentCard :apartment="apartment"/>
                    </div>
                </div>
            </div>
        

            <div v-if="nonSponsoredApartmentsArray.length > 0">
            <!-- Visualizza gli appartamenti non sponsorizzati -->
                <h1>Altri Appartamenti</h1>
                <div class="row">
                    <div v-for="apartment in nonSponsoredApartmentsArray" :key="apartment.id" class="col-12 col-md-6 col-lg-4 mb-4">
                        <ApartmentCard :apartment="apartment"/>
                    </div>
                </div>
            </div>

            <div class="no-apartments" v-if="sponsoredApartmentsArray.length === 0 && nonSponsoredApartmentsArray.length === 0">
            <h1 class="text-center mt-5">Nessun appartamento trovato!</h1>
            </div>
       
    </div>
     
            
</template>
  
<style lang="scss">
    .my-checkbox{
        display: inline-block;
        margin-right: 10px;
        cursor: pointer;
    }
  
    .btn-service{
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #fff;
        transition: background-color 0.3s;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
  
    .service-slider{
        display: flex;
        transition: transform 0.3s ease;
    }
  
    .slider-item{
        flex: 0 0 auto;
        margin-right: 10px;
    }
  
    .slider-control{
        background: transparent;
        border: none;
        font-size: 24px;
        cursor: pointer;
    }
  
    .slider-control:focus{
        outline: none;
    }
    .no-apartments{
        opacity: 0; /* Imposta l'opacità a 0 per nascondere inizialmente il div */
        transition: opacity 0.3s ease; /* Aggiungi una transizione per l'opacità */
        animation: fadeIn 0.3s ease-in-out 0.3s forwards; /* Applica un ritardo di 1 secondo prima dell'inizio dell'animazione */
    }

    @keyframes fadeIn{
    to {
        opacity: 1; /* Alla fine dell'animazione, imposta l'opacità a 1 per mostrare il div */
    }
    }
  
    @media (min-width: 768px) {
        .service-slider{
            width: calc(100% / 9);
        }
    }
  </style>
  