<?php

namespace App\Http\Controllers;


//import Model "Post
use App\Models\User;

//return type View
use Illuminate\View\View;

use Illuminate\Http\Request;

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
}
