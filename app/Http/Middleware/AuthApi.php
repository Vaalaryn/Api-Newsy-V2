<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
class AuthApi
{

public function handle(Request $request, Closure $next)
    {
        if(DB::table('users')->select('*')
            ->where('mail', $request->input('mail'))
            ->where('token', $request->input('token'))
            ->where('token_limit', '>', Carbon::now())
            ->count() == 0) {
            abort(Config::get('constante.type_retour.abort'));
        }else{
            return $next($request);
        }
    }
}
