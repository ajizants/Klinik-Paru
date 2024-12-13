<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('home');
        } else {
            return view('login');
        }
    }

    public function actionlogin(Request $request)
    {
        $email = $request->input('email');

        if (!str_ends_with($email, '@rsparu.com')) {
            $email .= '@rsparu.com';
        }

        $data = [
            'email' => $email,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($data)) {
            // Ambil email dari input request
            $email = $request->input('email');

            // Cek apakah email sama dengan 'nurse@rsparu.com'
            if ($email == 'nurse@rsparu.com' || $email == 'nurse') {
                // dd($email);
                return redirect('/surat/medis');
            }
            return redirect('home');
        } else {
            session()->flash('error', 'Email atau Password Salah');

            return redirect('/');
        }
    }
    // public function actionlogin(Request $request)
    // {
    //     $email = $request->input('email');

    //     if (!str_ends_with($email, '@rsparu.com')) {
    //         $email .= '@rsparu.com';
    //     }

    //     $data = [
    //         'email' => $email,
    //         // 'email' => $request->input('email'),
    //         // 'name' => $request->input('name'),
    //         'password' => $request->input('password'),
    //     ];

    //     if (Auth::Attempt($data)) {
    //         return redirect('home');
    //     } else {
    //         session()->flash('error', 'Email atau Password Salah');
    //         return redirect('/');
    //     }
    // }

    public function actionlogout()
    {
        Auth::logout();
        return redirect('/');
    }
}
