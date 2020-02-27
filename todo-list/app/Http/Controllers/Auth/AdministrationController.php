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
        $this->attemptLogin($request);

        if($this->authorizeForUser($this->guard()->user(), 'hasRole', ['admin'])) {
            $users = User::EmailIs($request->query('checked_email'))
            // By using get, we're returning a Collection which can then be converted into an
            // array directly. It means we don't need a conditional "if user is found" and
            // can go right to the call to `reset`
            ->get()
            ->toArray();

            return json_encode(['userData' => reset($users)], 128);
        }
    }
}