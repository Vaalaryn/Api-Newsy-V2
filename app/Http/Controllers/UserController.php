<?php


namespace App\Http\Controllers;


use App\Http\Requests\AddUser;
use App\Http\Requests\ConnectUser;
use App\Http\Requests\DeleteUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
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
            return response(Lang::get('user.response.add.ok'), Config::get("constante.type_retour.ok"));
        } catch (\Exception $e) {
            Log::error($e);
            return response(Lang::get('user.response.add.not_ok'), Config::get("constante.type_retour.erreur"));
        }
    }

    public function connect(ConnectUser $request)
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
                    ->update(['token' => $token, 'token_limit' => (Carbon::now())->addDays(Config::get('constante.token.limit'))]);
                $user = DB::table('users')
                    ->select('username', 'mail', 'token', 'data')
                    ->where('mail', $request->input('mail'))
                    ->first();
                return response(json_encode($user), Config::get('constante.type_retour.ok'));
            }
        } catch (\Exception $e) {
            Log::error($e);
            return response(Lang::get('user.response.connect.not_ok'), Config::get('constante.type_retour.erreur'));
        }
    }

    public function delete(DeleteUser $request)
    {
        try{
            $user = DB::table('users')
                ->select('*')
                ->where('mail', $request->input('mail'))
                ->first();

            if ($user && Hash::check($request->input('password'), $user->password)) {
                DB::table('users')
                    ->where('mail', $request->input('mail'))
                    ->where('token', $request->input('token'))
                    ->delete();
                return response(Lang::get('user.response.delete.ok'), Config::get('constante.type_retour.ok'));
            }else{
                return response(Lang::get('user.response.delete.password'), Config::get('constante.type_retour.bad_request'));
            }
        }catch (\Exception $e){
            Log::error($e);
            return response(Lang::get('user.response.delete.not_ok'),  Config::get('constante.type_retour.erreur'));
        }
    }

    public function update()
    {
        return '';
    }

    private function randomToken(): string
    {
        $token = Str::random(Config::get('constante.token.length'));
        $validator = \Validator::make(['token' => $token], ['token' => 'unique:users']);
        if ($validator->fails()) {
            return $this->randomToken();
        }
        return $token;
    }
}