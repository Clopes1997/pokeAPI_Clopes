<?php

namespace App\Http\Requests\Pokemon;

use App\Models\Pokemon;
use Illuminate\Foundation\Http\FormRequest;

class ImportPokemonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pokemon_id' => ['nullable', 'integer', 'min:1'],
            'start_id' => ['nullable', 'integer', 'min:1'],
            'end_id' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->route('apiId')) {
                $apiId = (int) $this->route('apiId');
                $pokemon = Pokemon::withTrashed()->where('api_id', $apiId)->first();
                if ($pokemon && !$pokemon->trashed()) {
                    $validator->errors()->add('pokemon', 'Este Pokémon já foi importado');
                }
                return;
            }

            $pokemonId = $this->input('pokemon_id');
            $startId = $this->input('start_id');
            $endId = $this->input('end_id');

            if ($pokemonId) {
                return;
            }

            if ($startId && $endId) {
                if ($startId >= $endId) {
                    $validator->errors()->add('start_id', 'O intervalo informado é inválido.');
                    return;
                }

                $intervalSize = $endId - $startId + 1;
                if ($intervalSize > 100) {
                    $validator->errors()->add('start_id', 'Você pode importar no máximo 100 Pokémon por vez.');
                }
            } elseif ($startId || $endId) {
                $validator->errors()->add('start_id', 'O intervalo informado é inválido.');
            }
        });
    }
}
