<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('frontend/users/index');
    }

    public function create()
    {
        return view('frontend/users/create');
    }
    public function edit()
    {
        return view('frontend/users/edit');
    }
      public function edit_post()
    {
        return view('frontend/users/edit');
    }

}