<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('frontend/departments/index');
    }

    public function create()
    {
        return view('frontend/departments/create');
    }
    public function edit()
    {
        return view('frontend/departments/edit');
    }

}