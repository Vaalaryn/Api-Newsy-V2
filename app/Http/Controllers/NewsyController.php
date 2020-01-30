<?php


namespace App\Http\Controllers;


use App\Http\Requests\NewsyRequest;
use Illuminate\Support\Facades\Config;

class NewsyController
{
    public function request(NewsyRequest $request)
    {

        dd(json_decode('{"key": "data"}'));
        $client = new \GuzzleHttp\Client();

        $url = Config::get("constante.newsy.url") . Config::get("constante.newsy.endpoints")['th'] . '?apiKey=' . Config::get("constante.newsy.key");
        $datas = explode(" ", $request->input("params"));
        foreach ($datas as $key => $data) {
            $url .= "&" . $key . "=" . $data;
        }
        $request = $client->get($url);
        $response = $request->getBody();
        return response($response);
    }
}