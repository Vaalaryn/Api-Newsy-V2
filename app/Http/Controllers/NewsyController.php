<?php


namespace App\Http\Controllers;


use App\Http\Requests\NewsyRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

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
        $time = Carbon::now();
        $time->minute = $time->minute - Config::get('constante.newsy.limit.time');
        $count = DB::table('request')->select('*')->where('token', $request->input('token'))->where('request_timestamp', '>', $time)->count();
        if($count < Config::get('constante.newsy.limit.number')) {
            //TODO : ajout bdd si bonne requete
            $request = $client->get($url);
            $response = $request->getBody();
            return response($response, 200);
        }else {
            return response(Lang::get('user.response.newsy.too_many_request', ['time' => Config::get('constante.newsy.limit.time')]), 429);
        }
    }
}