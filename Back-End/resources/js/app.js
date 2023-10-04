import './bootstrap';
import '~resources/scss/app.scss';
import * as bootstrap from 'bootstrap';
import.meta.glob([
    '../img/**'
])



// Seleziona tutti gli elementi con la classe "truncate-text"
let descriptElements = document.querySelectorAll('.truncate-text');

// Specifica il numero massimo di caratteri
let maxLength = 50;

// Itera su tutti gli elementi e applica la funzione a ciascuno
descriptElements.forEach(function (descriptElement) {
    let descript = descriptElement.textContent;

    // Controlla se la lunghezza del testo supera il numero massimo di caratteri
    if (descript.length > maxLength) {
        // Tronca il testo alla lunghezza massima
        descript = descript.substr(0, maxLength) + '...';
    }

    // Assegna il testo troncato all'elemento HTML
    descriptElement.textContent = descript;
});

//RECUPERO TUTTI I PULSANTI DI CANCELLAZIONE DELLA TABELLA DEI PROGGETTI
const deleteButton = document.querySelectorAll('.delete-apartment-form button[type="submit"]');

//CICLO TUTTI I PULSANTI
deleteButton.forEach((button) => {

    //AD OGNI PULSANTE GLI DICO DI RIMANERE IN ATTESA DI UN EVENTO CLICK. IMPORTANTISSIMO
    button.addEventListener('click', (event) => {
        event.preventDefault();

        //RECUPER L'HTML DELLA MODALE
        const modal = document.getElementById('confirmdelete');

        //RECUPERO IL DATA ATTRIBUTE CHE HO DEFINITO NEL PULSANTE
        const apartmentTitle = button.getAttribute('data-apartment-title');

        //CREO L'ISTANZA DELLA CLASSE MODAL DI BOOTSTRAP A PARTIRE DELL'HTML CHE HO RECUPERATO NEL PASSAGGIO PRECEDENTE
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();

        //RECUPERO LO SAPN DEFINITO NELLA MODALE CHE DEVE CONTENERE IL TITOLO
        const titleApartment = modal.querySelector('#data-apartment-title');

        //ORA CHE HO RECUPERATO IL TITOLO DEL PROGGETTO VADO A METTERE IL TITOLO
        titleApartment.textContent = apartmentTitle

        //RECUPERO IL PULSANTE DI CANCELLAZIONE PRESENTE NELLA MODALE
        const buttonDelete = document.querySelector('.confirm-delete-button');

        //DEVO FAR SI CHE QUESTO NUOVO PULSANTE RESTI IN ATTESA DI UN NUOVO EVENTO
        buttonDelete.addEventListener('click', () => {
            //DAL PULSANTE RECUPERO L'ANTENATO E SOTTOMETTO LA FORM
            button.parentElement.submit();
        })
    })
});


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
});

// Controllo lato client servizi
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('apartment-form');
    form.addEventListener('submit', function (event) {
        const selectedServices = document.querySelectorAll('input[name="services[]"]:checked');
        if (selectedServices.length === 0) {
            servicesError.textContent = 'Seleziona almeno un servizio.';
            event.preventDefault(); // Prevent form submission if no services are selected
        }
    });
});

