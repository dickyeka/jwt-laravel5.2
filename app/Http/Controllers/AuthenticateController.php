<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;

class AuthenticateController extends Controller
{

    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        $this->middleware('jwt.auth', ['except' => ['authenticate']]);
    }

    /**
     * Return the user
     *
     * @return Response
     */
    public function index()
    {
        $users = User::all();
        $response = response()->json([
                'code'      => 200,
                'error'     => false,            
                'message'   => 'ok',
                'data'      => $users,   
            ]);
        $response->header('Content-Type', 'application/json');
        return $response;
        
    }

    /**
     * Return a JWT
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                $response = response()->json([
                    'code'      => 401,
                    'error'     => true,            
                    'message'   => 'invalid_credentials'],
                    401
                );
                $response->header('Content-Type', 'application/json');
                return $response;
            }
            else{
                $user=User::where('email','=',$request->get('email'))->first();
                $data=[
                    'user'  =>$user,
                    'token' =>$token
                ];
            }
        } catch (JWTException $e) {
            // something went wrong
            $response = response()->json([
                    'code'      => 500,
                    'error'     => true,            
                    'message'   => 'could_not_create_token'],
                    500
                );
            $response->header('Content-Type', 'application/json');
            return $response;
        }

        $response = response()->json([
                'code'      => 200,
                'error'     => false,            
                'message'   => 'ok',
                'data'      => $data],
                200   
            );
        $response->header('Content-Type', 'application/json');
        return $response;   
    }
}