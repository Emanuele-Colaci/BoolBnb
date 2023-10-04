<script>
import { router } from '../router.js';
export default {
    data() {
      return {
        name: 'HomePage',
        router
      }
    },
    mounted() {
      // Seleziona l'header
      const header = document.getElementById('myHeader');

      // Aggiungi un gestore di eventi alla finestra per rilevare lo scroll
      window.addEventListener('scroll', function () {
      // Se lo scroll supera una certa soglia, aggiungi la classe per il background bianco
      if (window.scrollY > 1) {
          header.classList.add('header-scrolled');
      } else {
          // Altrimenti, rimuovi la classe per tornare al background trasparente
          header.classList.remove('header-scrolled');
      }
    })
  }
}
</script>
<template>
  <div>
    <header id="myHeader" class="p-0 navbar navbar-expand-md fixed-top">
            <div class="container d-flex justify-content-between align-items-center">
                <router-link class="navbar-brand d-flex align-items-center p-0" :to="{ name:'home' }">
                    <div class="logo">
                        <img class="logo-img" src="/images/LogoNoSfondo3.png" alt="boolbnb"> <span class="letter-spacing">BoolBnB</span>
                    </div>
                </router-link>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <div class="navbar-nav me-auto">
                      
                    </div>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                      
                        <li class="nav-item">
                            <a class="nav-link" href="http://127.0.0.1:8000/login">Accesso</a>
                        </li>
                      
                        <li class="nav-item">
                            <a class="nav-link" href="http://127.0.0.1:8000/register">Registrazione</a>
                        </li>

                  
                    </ul>
                </div>
            </div>
        </header>
  </div>
</template>
<style lang="scss">
  
  #myHeader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    transition: background-color 0.3s ease;
    box-shadow: 2px 2px 5px rgba(215, 125, 133, 0.75);
  }
  .header-scrolled {
    background-color: rgba(215, 125, 133, 0.95);
  }

  .logo{
      cursor: pointer;
  }

  .logo-img{
      height: 3.75rem;
      margin: .625rem 0;
  }

  .navbar{
      border-bottom: 0.125rem solid var(--custom-black);
  }
  .ms-dropdown{
      border-radius: 0;
  }
  main{
      min-height: calc(100vh - 210px);
  }

  .margin-top-header {
      margin-top: 100px;
  }

  .letter-spacing{
    letter-spacing: 3px;
  }
</style>
