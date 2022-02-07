<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait PokemonRequest
{
    public function pokemonList(int $offset, int $limit)
    {

        $response = Http::get($this->getBaseUrl() . 'pokemon', [
            'offset' => $offset,
            'limit' => $limit
        ]);

        if ($response->status() == 200) {
            $data = $response->json();


            $cont = 0;
            while ($cont < sizeof($data['results'])) {
                $data['results'][$cont]['name'] = Str::ucfirst($data['results'][$cont]['name']);
                $data['results'][$cont]['image_url'] = $this->getImage($data['results'][$cont]['url']);
                $cont++;
            }


            return response()->json(['status' => 'success', 'data' => $data]);
        } else {
            return response()->json(['status' => 'fail', 'data' => []]);
        }
    }

    public function pokemonInfo($url)
    {
        $response = Http::get($url);
        if ($response->status() == 200) {
            $data = $response->json();
            return response()->json(['status' => 'success', 'data' => $data]);
        } else {
            return response()->json(['status' => 'fail'], 404);
        }
    }

    private function getBaseUrl()
    {
        return "https://pokeapi.co/api/v2/";
    }

    public function getImage($url)
    {
        $image =  explode('/', explode('https://pokeapi.co/api/v2/pokemon/', $url)[1])[0];
        return "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/$image.png";
    }
}
