<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;

class LoginController extends Controller
{
    public function login()
    {
        if (Users::check()) {
            return redirect('homepage');
        } else {
            return view('login');
        }
    }

    public function actionlogin(Request $request)
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (Users::Attempt($data)) {
            return redirect('homepage');
        } else {
            return redirect('login');
        }
    }

    public function actionlogout()
    {
        Users::logout();
        return redirect('login');
    }
}
