<?php
namespace App\Http\Controllers;

use App\Models\LoginLogModel;
use Illuminate\Auth\Events\Login;
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

        if (! str_ends_with($email, '@rsparu.com')) {
            $email .= '@rsparu.com';
        }

        $data = [
            'email'    => $email,
            'password' => $request->input('password'),
        ];
        // dd($data);

        if (Auth::attempt($data)) {
            $user = Auth::user();

            $log = LoginLogModel::create([
                'user_id'      => $user->id,
                'ip_address'   => $request->ip(),
                'user_agent'   => $request->header('User-Agent'),
                'logged_in_at' => now(),
            ]);

            // Simpan ID log di session
            session(['login_log_id' => $log->id]);

            $email = $request->input('email');
            if (! str_ends_with($email, '@rsparu.com')) {
                $email .= '@rsparu.com';
            }
            // dd($email);
            $role = Auth::user()->role;
            $go   = "";
            switch ($email) {
                case 'nurse@rsparu.com':
                    $go = '/surat/medis';
                    break;
                case 'tindakan@rsparu.com':
                    $go = '/Igd';
                    break;
                case 'kasir@rsparu.com':
                    $go = '/kasir';
                    break;
                case 'radiologi@rsparu.com':
                    $go = '/Radiologi';
                    break;
                case 'dots@rsparu.com':
                    $go = '/dots';
                    break;
                case 'lab@rsparu.com':
                    $go = '/Laboratorium/Pendaftaran';
                    break;
                case 'farmasi@rsparu.com':
                    $go = '/farmasi';
                    break;
                case 'promkes@rsparu.com':
                    $go = '/Promkes';
                    break;
                case 'dataanalis@rsparu.com' || 'admin@rsparu.com':
                    $go = '/Pusat_Data';
                    break;
                default:
                    $go = '/home';
            }
            if ($role == 'pegawai' || $role == 'tu') {
                $go = '/TataUsaha/Cuti';
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
        $logId = session('login_log_id');

        if ($logId) {
            LoginLogModel::where('id', $logId)->update([
                'logged_out_at' => now(),
            ]);
        }
        Auth::logout();
        session()->forget('login_log_id');
        return redirect('/');
    }

    public function logoutSession()
    {
        $logId = session('login_log_id');

        if ($logId) {
            LoginLogModel::where('id', $logId)->update([
                'logged_out_at' => now(),
            ]);
        }

        Auth::logout();
        session()->flush(); // hapus semua session
        return redirect('/')->with('message', 'Sesi Anda telah habis, silakan login kembali.');
    }

}
