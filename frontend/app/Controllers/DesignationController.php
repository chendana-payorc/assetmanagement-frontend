<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DesignationController extends Controller
{
    public function index()
    {
        return view('frontend/designation/index');
    }

    public function create()
    {
        return view('frontend/designation/create');
    }
    public function edit()
    {
        return view('frontend/designation/edit');
    }

}