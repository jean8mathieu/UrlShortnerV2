<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller implements HasMiddleware
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to the admin dashboard.
    |
    */

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
        ];
    }

    public function login() {
        return view('auth.login');
    }

    public function postLogin(Request $request){
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
           return Redirect::to(route('login'))
               ->withInput($request->only('username'))
               ->with('error', 'Please enter your username and password.');
        }

        $userdata = ['username' => $request->username, 'password' => $request->password];

        if(Auth::attempt($userdata)) {
            return Redirect::to(route('dashboard'));
        } else {
            return Redirect::to(route('login'))
                ->withInput($request->only('username'))
                ->with('error', 'Invalid username or password.');
        }
    }

    public function logout(){
        Auth::logout();
        return Redirect::to('home');
    }
}
