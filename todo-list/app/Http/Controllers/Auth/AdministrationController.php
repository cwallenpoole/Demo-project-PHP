<?php

namespace App\Http\Controllers\Auth;

use \App\Http\Controllers\Controller;
use \App\Providers\RouteServiceProvider;
use \App\User;
use \Illuminate\Http\Request;
use \Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdministrationController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('signed')->only('verify');
    }


    /**
     * Useful if an admin wants to check if a user exists.
     *
     * @param Request $request
     * @return string
     */
    public function doesEmailExist(Request $request) {
        if($this->authorizeForUser($this->guard()->user(), 'hasRole', ['admin'])) {
            $users = User::EmailIs($request->query('email_address'))
            // By using get, we're returning a Collection which can then be converted into an
            // array directly. It means we don't need a conditional "if user is found" and
            // can go right to the call to `reset`
            ->get()
            ->toArray();

            return response()->json([
                'userData' => reset($users),
            ], 200, [], 128);
        }
    }

    /**
     * Useful for checking on a specific API token.
     *
     * @param Request $request
     */
    public function isTokenValid(Request $request) {
        if($this->authorizeForUser($this->guard()->user(), 'hasRole', ['admin'])) {
            $token = $request->query('token');
            $user = User::TokenIs($token)->first();

            if(!$user) {
                return response()->json([
                    'userData' => false,
                    'message' => 'Token not found'
                ], 200, [], 128);
            } elseif($token !== $user->api_token) {
                return response()->json([
                    'userData' => $user->toArray(),
                    'message' => 'Token expired. New token generated.'
                ], 200, [], 128);
            }

            return response()->json([
                'userData' => $user->toArray(),
                'message' => 'Token is current'
            ], 200, [], 128);
        }
    }
}