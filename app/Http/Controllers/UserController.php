<?php
namespace App\Http\Controllers;

//import Model "Post

use App\Models\LoginLogModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $title       = 'User';
        $users       = $this->getUsers();
        $usersOnline = $this->getUserOnline();

        //render view with users
        return view('User.main', compact('users', 'usersOnline', 'title'));
    }
    public function userOnline()
    {

        $users = $this->getUserOnline();
        return response()->json(['users' => $users]);
    }

    public function getUserOnline()
    {
        $logs = LoginLogModel::with('user')->orderby('logged_in_at', 'desc')->get();
        $html = '
        <table class="table table-striped table-hover pt-0 mt-0" style="width:100%" cellspacing="0" id="tableUserOnline">
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
    public function getUsers()
    {
        $datas = User::get();
        $html  = '
        <table class="table table-striped table-hover pt-0 mt-0" style="width:100%" cellspacing="0" id="tableUser">
            <thead>
                <tr>
                    <th>Aksi</th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Email at</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($datas as $d) {
            $html .= '<tr>
                        <td>
                            <a type="button" class="btn btn-sm btn-primary" onclick="showEditUserModal(' . $d->id . ')">Edit</a>
                        </td>
                        <td>' . $d->id . '</td>
                        <td>' . $d->name . '</td>
                        <td>' . $d->email . '</td>
                        <td>' . date('d-m-Y H:i:s', strtotime($d->email_verified_at)) . '</td>
                        <td>' . date('d-m-Y H:i:s', strtotime($d->created_at)) . '</td>
                        <td>' . date('d-m-Y H:i:s', strtotime($d->updated_at)) . '</td>
                    </tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

    public function create()
    {
        return view('User.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'role'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'role'     => $validated['role'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'User berhasil ditambahkan.',
            'user'    => $user,
        ], 201); // Status 201 Created
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        // Ambil semua role unik, kecuali 'admin'
        $roles = User::where('role', '!=', 'admin')->distinct()->pluck('role');

        $csrf = csrf_token();

        // Generate options HTML
        $roleOptions = '';
        foreach ($roles as $role) {
            $selected = $user->role === $role ? 'selected' : '';
            $roleOptions .= "<option value=\"{$role}\" {$selected}>{$role}</option>";
        }

        return <<<HTML
            <form id="editUserForm"  action="/users/{$id}" >
                <input type="hidden" name="_token" value="{$csrf}">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" value="{$user->id}">

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{$user->name}" required>
                </div>


                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        {$roleOptions}
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{$user->email}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password <small>(kosongkan jika tidak ingin diubah)</small></label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password">
                        <div class="input-group-append">
                            <button class="btn btn-secondary toggle-password" type="button" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        <div class="input-group-append">
                            <button class="btn btn-secondary toggle-password" type="button" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-primary" onclick="updateUser({$user->id})">Update</button>
                </div>
            </form>
        HTML;
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'nullable|string|max:255',
            'role'     => 'nullable|string',
            'email'    => 'email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'role', 'email']);

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $users = $this->getUsers();

        return response()->json(['message' => 'User updated successfully', 'users' => $users], 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

}
