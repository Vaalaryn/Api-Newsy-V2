<?php


namespace App\Http\Controllers;


use App\Http\Requests\AddUser;
use App\Http\Requests\ConnectUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController
{
    public function add(AddUser $request)
    {
        $data = [
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'mail' => $request->input('mail'),
            'data' => ($request->input('data') != "") ? $request->input('data') : "{}"
        ];
        try {
            DB::table('users')->insert($data);
            return response("ok", Config::get("constante.type_retour.ok"));
        } catch (\Exception $e) {
            return response($e, Config::get("constante.type_retour.erreur"));
        }
    }

    public function connect(Request $request)
    {
        try {
            $user = DB::table('users')
                ->select('*')
                ->where('mail', $request->input('mail'))
                ->first();
            if ($user && Hash::check($request->input('password'), $user->password)) {
                $token = $this->randomToken();
                DB::table('users')
                    ->where('mail', $request->input('mail'))
                    ->update(['token' => $token, 'token_limit' => (Carbon::now())->addDays(7)]);
                $user = DB::table('users')
                    ->select('username', 'mail', 'token', 'data')
                    ->where('mail', $request->input('mail'))
                    ->first();
                return response(json_encode($user), Config::get('constante.type_retour.ok'));
            }
        } catch (\Exception $e) {
            Log::error($e);
            return response("Connexion impossible", Config::get('constante.type_retour.erreur'));
        }
    }

    public function delete()
    {
        return "delete";
    }

    public function update()
    {
        return 'update';
    }

    /**
     * @return mixed
     */
    public function randomToken(): string
    {
        $token = Str::random(22);
        $validator = \Validator::make(['token' => $token], ['token' => 'unique:users']);
        if ($validator->fails()) {
            Log::debug("Token failed");
            return $this->randomToken();
        }
        return $token;
    }
}