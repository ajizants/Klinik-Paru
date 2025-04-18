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
            $email = $request->input('email');
            if (!str_ends_with($email, '@rsparu.com')) {
                $email .= '@rsparu.com';
            }

            $go = "";
            switch ($email) {
                case 'nurse@rsparu.com':
                    $go = '/E-kinerja';
                    break;
                case 'tindakan@rsparu.com':
                    $go = '/igd';
                    break;
                case 'kasir@rsparu.com':
                    $go = '/kasir';
                    break;
                case 'radiologi@rsparu.com':
                    $go = '/ro';
                    break;
                case 'dots@rsparu.com':
                    $go = '/dots';
                    break;
                case 'lab@rsparu.com':
                    $go = '/lab';
                    break;
                case 'farmasi@rsparu.com':
                    $go = '/farmasi';
                    break;
                case 'dataanalis@rsparu.com' || 'admin@rsparu.com':
                    $go = '/Pusat-Data';
                    break;
                default:
                    $go = '/home';
            }

            return redirect($go);
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
