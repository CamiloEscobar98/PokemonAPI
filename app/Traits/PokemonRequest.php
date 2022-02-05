<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait PokemonRequest
{
    public function pokemonList()
    {
        $response = Http::get($this->getBaseUrl() . 'pokemon');

        if ($response->status() == 200) {
            return response()->json(['status' => 'success', 'data' => $response->json()]);
        } else {
            return response()->json(['status' => 'fail', 'data' => []]);
        }
    }

    private function getBaseUrl()
    {
        return "https://pokeapi.co/api/v2/";
    }
}
