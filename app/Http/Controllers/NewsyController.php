<?php


namespace App\Http\Controllers;


use App\Http\Requests\NewsyRequest;

class NewsyController
{
    public function request(NewsyRequest $request)
    {

        return response("", 200);
    }
}