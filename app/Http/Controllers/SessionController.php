<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class SessionController extends Controller
{
    //
    public function checkSess(Request $request) {
        $user = array('name' => '',
            'token' => '',
            'auth' => '0',
            'image' => 'http://jimac-test-suite.test/public/images/default.png'
        );

        if(!empty($request->input('token'))) {
            foreach(User::where('remember_token', $request->input('token'))->cursor() as $user) {
                if($user->status) {
                    $userdata = array(
                        'name' => $user->name,
                        'token' => $user->remember_token,
                        'auth' => $user->auth,
                        'image' => 'http://jimac-test-suite.test/public/images/default.png'
                    );

                    session('user', $userdata);
                } else 
                    return response()->json([
                        'status' => '2', 
                        'user' => [], 
                        'message' => 'User is deactivated!']);
            }
        }
        
        if(session('user')) {
            $userdata = $request->session()->get('user');
        }
        
        return response()->json(['status' => 1,'user' => $userdata]);
    }
}
