<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'n_rooms' => 'required|integer|min:1',
            'n_beds' => 'required|integer|min:1',
            'n_bathrooms' => 'required|integer|min:1',
            'mq' => 'numeric|min:1',
            'address' => 'required|string|max:255',
            'price' => 'numeric|min:0|nullable',
            'description' => 'string|nullable',
            'img' => 'image|mimes:jpeg,png,jpg,gif',
            'services' => 'array|required_without:services.*'
        ];
    }

    public function messages(){
        return[
            'title.required' => 'Il campo "Titolo" è obbligatorio',
            'title.max' => 'Il campo "Titolo" deve contenere al massimo :max caratteri',
            'n_rooms.required' => 'Il campo "Numero di stanze" è obbligatorio',
            'n_rooms.integer' => 'Il campo "Numero di stanze" deve essere un numero intero',
            'n_rooms.min' => 'Il campo "Numero di stanze" deve essere almeno :min',
            'n_beds.required' => 'Il campo "Numero di letti" è obbligatorio',
            'n_beds.integer' => 'Il campo "Numero di letti" deve essere un numero intero',
            'n_beds.min' => 'Il campo "Numero di letti" deve essere almeno :min',
            'n_bathrooms.required' => 'Il campo "Numero di bagni" è obbligatorio',
            'n_bathrooms.integer' => 'Il campo "Numero di bagni" deve essere un numero intero',
            'n_bathrooms.min' => 'Il campo "Numero di bagni" deve essere almeno :min',
            'mq.numeric' => 'Il campo "Metri quadrati" deve essere un numero',
            'mq.min' => 'Il campo "Metri quadrati" deve essere almeno :min',
            'address.required' => 'Il campo "Indirizzo" è obbligatorio',
            'address.max' => 'Il campo "Indirizzo" deve contenere al massimo :max caratteri',
            'price.numeric' => 'Il campo "Prezzo" deve essere un numero e non sono ammesse virgole "," per i numeri decimali',
            'price.min' => 'Il campo "Prezzo" deve essere almeno :min',
            'description.string' => 'Il campo "Descrizione" deve essere una stringa',
            'img.image' => 'Il campo "Immagine" deve essere un\'immagine',
            'img.mimes' => 'Il campo "Immagine" deve essere un file di uno dei seguenti tipi: jpeg, png, jpg, gif',
            'services.array' => 'Seleziona almeno un servizio.',
            'services.required_without' => 'Seleziona almeno un servizio.',
        ];
    }
}
