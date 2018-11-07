<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\User;

class UserController extends Controller
{
    //
    public function registerUser(Request $request) {
        
    }

    public function loginUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        $names = array('username'=>"Username",
            'password'=>"Password");

        $validator->setAttributeNames($names);

        if($validator->fails()) return response()->json(["status"=>0,"errors"=>$validator->errors()]);

        try {
            $user = User::where('email', $request->input("username"))->firstOrFail();

            if(password_verify($request->input("password"), $user->password)) {
                $user->remember_token = $this->quickRandom();
                $user->save();
                return ["status"=>1, "auth"=>$user->auth, "token"=>$user->remember_token];
            } else {
                return ["status"=>2, "error"=>"User not found!"];
            }
        } catch(ModelNotFoundException $e) {
            try {
                $user = User::where('username', $request->input("username"))->firstOrFail();
                
                if(password_verify($request->input("password"), $user->password)) {
                    $user->remember_token = $this->quickRandom();
                    $user->save();
                    return ["status"=>1, "auth"=>$user->auth, "token"=>$user->remember_token];
                } else {
                    return ["status"=>2, "error"=>"User not found!"];
                }
            } catch(ModelNotFoundException $e) {
                return ["status"=>2, "error"=>"User not found!"];
            }
        }
    }

    public static function quickRandom($length = 16) {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
