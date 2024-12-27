<?php

namespace App\Http\Controllers;

//import Model "Post
use App\Models\userbpjs;
//return type View
use Illuminate\View\View;

class UserBpjsController extends Controller
{

    public function index()
    {
        $users = userbpjs::all();
        return view('bpjs.index', compact('users'));
    }
}
