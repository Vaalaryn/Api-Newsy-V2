<?php


namespace App\Http\Controllers;


use App\Http\Requests\NewsyRequest;
use Illuminate\Support\Facades\Config;

class NewsyController
{
    public function request(NewsyRequest $request)
    {
        $client = new \GuzzleHttp\Client();
        $endpoint = $request->input('endpoint');
        $datas = json_decode($request->input('params'));

        $url = Config::get("constante.newsy.url") . $endpoint . '?apiKey=' . Config::get("constante.newsy.key");
        
        foreach ($datas as $key => $data) {
            if ((array_key_exists($key, Config::get('constante.newsy.valid_params' . $endpoint . 'restricted'))
                    && in_array($data, Config::get('constante.newsy.valid_params' . $endpoint . 'restricted' . $key)))
                || in_array($key, Config::get('constante.newsy.valid_params' . $endpoint . 'free'))) {
                $url .= "&" . $key . "=" . $data;
            }
        }
        $request = $client->get($url);
        $response = $request->getBody();
        return response($response);
    }
}