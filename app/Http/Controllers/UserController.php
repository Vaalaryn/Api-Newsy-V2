<?php


namespace App\Http\Controllers;


use App\Http\Requests\AddUser;
use App\Http\Requests\ConnectUser;
use App\Http\Requests\DeleteUser;
use App\Http\Requests\UpdateUser;
use Carbon\Carbon;
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
        try {
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
            } else {
                return response(Lang::get('user.response.delete.password'), Config::get('constante.type_retour.bad_request'));
            }
        } catch (\Exception $e) {
            Log::error($e);
            return response(Lang::get('user.response.delete.not_ok'), Config::get('constante.type_retour.erreur'));
        }
    }

    public function update(UpdateUser $request)
    {
        $update = [];
        $user = DB::table('users')
            ->where('mail', $request->input('mail'))
            ->where('token', $request->input('token'))
            ->first();
        $field_to_update = json_decode($request->input('field_to_update'), true);
        if ($user && Hash::check($request->input('password'), $user->password)) {
            if (array_key_exists('mail', $field_to_update)) {
                $validator = \Validator::make(['mail' => $field_to_update['mail']], ['mail' => Config::get('constante.validation.mail_unique')]);
                if (!$validator->fails()) {
                    $update['mail'] = $field_to_update['mail'];
                }
            }
            if (array_key_exists('password', $field_to_update)) {
                $validator = \Validator::make([
                    'password' => $field_to_update['password'],
                    'password_confirm' => $field_to_update['password_confirm']
                ], [
                    'password' => Config::get('constante.validation.password'),
                    'password_confirm' => Config::get('constante.validation.password_confirm')
                ]);
                if (!$validator->fails()) {
                    $update['password'] = Hash::make($field_to_update['password']);
                }
            }
            if (array_key_exists('username', $field_to_update)) {
                $validator = \Validator::make(['username' => $field_to_update['username']], ['username' => Config::get('constante.validation.username')]);
                if (!$validator->fails()) {
                    $update['username'] = $field_to_update['username'];
                }
            }

        }
        try {
            DB::table('users')
                ->where('mail', $user->mail)
                ->update($update);
        } catch (\Exception $e) {
            Log::error($e);
            return response(Lang::get('user.response.update.not_ok'), Config::get('constante.type_retour.erreur'));
        }
        return response(Lang::get('user.response.update.ok'), Config::get('constante.type_retour.ok'));
    }

    public function updateData($request)
    {
        try {
            $update['data'] = $request->input('data');
            DB::table('users')->where('mail', $request->input('mail'))->update($update);
            return response("", Config::get('constante.type_retour.ok'));
        } catch (\Exception $e) {
            Log::error($e);
            return response(Lang::get('user.response.update.ok'), Config::get('constante.type_retour.erreur'));
        }
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