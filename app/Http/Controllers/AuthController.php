<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function postLogin(Request $request){
      
        $emailOrName = $request->input('email');
        $password = $request->input('password');

        // Determine if input is likely an email address
        $isEmail = filter_var($emailOrName, FILTER_VALIDATE_EMAIL);

        // Define the credentials array based on input type
        if ($isEmail) {
            $credentials = ['email' => $emailOrName];
        } else {
            $credentials = ['name' => $emailOrName];
        }
       
        $credentials['password'] = $password;
       
        // Attempt authentication
        if (Auth::attempt($credentials)) {
            // Authentication successful
            $user = Auth::user();

            // Check user status
            if ($user->is_active == '1') {
                // Update last login

                  //update last login
                  $update_lastlogin=User:: where('email',$user->email)
                  ->update([
                      'last_login' => now(),
                      'login_counter' =>  $user->login_counter + 1,
                  ]);

                // Redirect to home page
                return redirect('/home');
            } else {
                // User is not active, redirect with message
                return redirect('/')->with('statusLogin', 'Give Access First to User');
            }
        } else {
            // Authentication failed, redirect with message
            return redirect('/')->with('statusLogin', 'Wrong Email/Name or Password');
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('statusLogout','Success Logout');
    }
}
