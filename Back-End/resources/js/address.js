import axios from 'axios';

let autocompleteList = document.getElementById('autocomplete-list');

const input = document.getElementById('address');

input.addEventListener("keyup", function () {

    let userInput = document.getElementById('address').value;

    if (userInput.trim().length < 3) {
        return;
    }

input.addEventListener("input", function() {
    if (input.value === '') {
        autocompleteList.innerHTML = '';
        }
    });

    const apiUrl = 'https://api.tomtom.com/search/2/geocode/';

    delete axios.defaults.headers.common['X-Requested-With'];

    axios.get(apiUrl + userInput + '.json', {
        params: {
            key: 'HmfWKAQTl23dOsGqCArlxGZ4o2Jx6Q02',
            typeahead: true,
            countrySet: 'IT'
        }
    }).then(function (response) {

        console.log(response.data.results);
        const results = response.data.results;

        // Svuota il container della lista
        autocompleteList.innerHTML = '';

        // Creo i nuovi elementi della lista
        for (let i = 0; i < results.length; i++) {
            const resultList = results[i].address.freeformAddress;
            const liElement = document.createElement('li');
            liElement.innerHTML = resultList;
            liElement.classList.add('list-group-item');
            liElement.classList.add('list-group-item-action');
            liElement.style = 'cursor: pointer';
            liElement.addEventListener('click', function () {
                input.value = resultList;
                autocompleteList.innerHTML = '';
            });
            autocompleteList.appendChild(liElement);
        }
        // Verifica se il numero di risultati supera 5
        if (results.length > 5) {
        // Aggiunge una classe CSS per attivare la scrollbar
        autocompleteList.classList.add('scrollbar');
        } else {
        // Rimuove la classe CSS per disattivare la scrollbar
        autocompleteList.classList.remove('scrollbar');
        }

    }).catch(function (error) {
        console.log(error);
    });

});