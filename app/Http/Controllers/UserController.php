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

    public function checkSess(Request $request) {
        $userdata = array('name' => '',
            'token' => '',
            'auth' => '0',
            'image' => 'http://jimac-test-suite.test/storage/images/default.png'
        );

        if(!empty($request->input('token'))) {
            foreach(User::where('remember_token', $request->input('token'))->cursor() as $user) {
                if($user->status) {
                    $userdata = array(
                        'name' => $user->name,
                        'token' => $user->remember_token,
                        'auth' => $user->auth,
                        'image' => 'http://jimac-test-suite.test/storage/images/default.png'
                    );

                } else 
                    return response()->json([
                        'status' => '2', 
                        'user' => [], 
                        'message' => 'User is deactivated!']);
            }
        }
        
        return response()->json(['status' => 1,'user' => $userdata]);
    }

    public function createTestUser() {
        $data = array(
            'sqa' => array(
                'name' => 'Ken Cosca (SQA)',
                'username' => 'sqa',
                'email' => 'kendrickjaviercosca@gmail.com',
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'status' => 1,
                'auth' => 1,
                'remember_token' => ''
            ),
            'pl' => array(
                'name' => 'Ken Cosca (Project Leader)',
                'username' => 'pl',
                'email' => 'witwiw0034@gmail.com',
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'status' => 1,
                'auth' => 2,
                'remember_token' => ''
            ),
            'pm' => array(
                'name' => 'Ken Cosca (Project Manager)',
                'username' => 'pm',
                'email' => 'royalhmtaxi@gmail.com',
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'status' => 1,
                'auth' => 3,
                'remember_token' => ''
            ),
            'su' => array(
                'name' => 'Ken Cosca (Admin)',
                'username' => 'su',
                'email' => 'kendrickandrewjavier@gmail.com',
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'status' => 1,
                'auth' => 4,
                'remember_token' => ''
            )
        );
        foreach($data as $value) {
            $user = new User;
            $user->name = $value['name'];
            $user->username = $value['username'];
            $user->email = $value['email'];
            $user->password = $value['password'];
            $user->status = $value['status'];
            $user->auth = $value['auth'];
            $user->remember_token = $value['remember_token'];
            $user->save();
        }
        return redirect('/');
    }

    public function loginUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        $names = array('username'=>'Username',
            'password'=>'Password');

        $validator->setAttributeNames($names);

        if($validator->fails()) 
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()
            ]);

        try {
            $user = User::where('email', $request->input('username'))->firstOrFail();

            if(password_verify($request->input('password'), $user->password)) {
                $user->remember_token = $this->quickRandom();
                $user->save();

                if($user->status) {
                    $responsedata = array('name' => $user->name,
                        'token' => $user->remember_token,
                        'auth' => $user->auth,
                        'image' => 'http://jimac-test-suite.test/storage/images/default.png'
                    );

                    return response()->json([
                        'status' => 1,
                        'user' => $responsedata
                    ]);
                } else {
                    return response()->json([
                        'status' => 2, 
                        'message' => 'User is deactivated!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 2, 
                    'message' => 'User not found!',
                    'errors' => array('username' => array(' '), 'password' => array('Invalid Username/Password'))
                ]);
            }
        } catch(ModelNotFoundException $e) {
            try {
                $user = User::where('username', $request->input('username'))->firstOrFail();
                
                if(password_verify($request->input('password'), $user->password)) {
                    $user->remember_token = $this->quickRandom();
                    $user->save();
                    
                    if($user->status) {
                        $responsedata = array('name' => $user->name,
                            'token' => $user->remember_token,
                            'auth' => $user->auth,
                            'image' => 'http://jimac-test-suite.test/storage/images/default.png'
                        );

                        return response()->json([
                            'status' => 1,
                            'user' => $responsedata
                        ]);
                    } else {
                        return response()->json([
                            'status' => 2, 
                            'message' => 'User is deactivated!'
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 2,
                        'message' => 'User not found!',
                        'errors' => array('username' => array(' '), 'password' => array('Invalid Username/Password'))
                    ]);
                }
            } catch(ModelNotFoundException $e) {
                return response()->json([
                    'status' => 2, 
                    'message' => 'User not found!',
                    'errors' => array('username' => array(' '), 'password' => array('Invalid Username/Password'))
                ]);
            }
        }
    }

    public static function quickRandom($length = 16) {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
