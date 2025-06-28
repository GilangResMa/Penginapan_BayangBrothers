<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class CekDB extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get posts
        $users = Users::all();

        //render view with posts
        return view('index', compact('users'));
    }
}