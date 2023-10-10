<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostUpsertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // recupero l'utente autenticato
        $user = Auth::user();

        // se l'email é florian.leica@gmail.com, lo faccio passare, altrimenti no.
        if ($user->email === "florian.leica@gmail.com") {
            // se ritorno true l'operazione viene permessa
            return true;
        }

        // ritornando false, l'operazione viene bloccata e ritorna un errore 403
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "required|max:255",
            "body" => "required",
            "image" => "nullable|image|max:6000",
            "image_link" => "nullable|max:255",
            "is_published" => "nullable|boolean",
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'E\' necessario specificare un titolo per il post',
            'title.max' => 'Mi sembra che il titolo sia un po\' troppo lungo',
            'body.required' => 'Di cosa parla questo post?',
            'image.required' => 'E\' difficile usare così tanto l\'immaginazione.',
            'image.max' => 'Immagine troppo grande. Il limite è 4MB',
        ];
    }
}
