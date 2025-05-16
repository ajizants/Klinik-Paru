<?php
namespace App\Http\Controllers;

//import Model "Post

use App\Models\LoginLogModel;
use App\Models\User;

//return type View
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index(): View
    {
        //get posts
        $posts = User::latest()->get();

        //render view with posts
        return view('inventaris.main', compact('posts'));
    }

    public function indexUserOnline()
    {
        $title = 'User Online';
        $data  = $this->userOnline();
        return view('PusatData.userOnline', compact('data', 'title'));
    }
    public function userOnline()
    {
        $logs = LoginLogModel::with('user')->orderby('logged_out_at', 'asc')->get();
        $html = '
        <table class="table table-bordered table-striped" id="tableUserOnline">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Email</th>
                    <th>IP Address</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($logs as $log) {
            $html .= '<tr>
                        <td>' . $no++ . '</td>
                        <td>' . e($log->user->email) . '</td>
                        <td>' . e($log->ip_address) . '</td>
                        <td>' . date('d-m-Y H:i:s', strtotime($log->logged_in_at)) . '</td>
                        <td>' . ($log->logged_out_at ? date('d-m-Y H:i:s', strtotime($log->logged_out_at)) : '<span class="text-danger">Belum Logout</span>') . '</td>
                    </tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }
}
