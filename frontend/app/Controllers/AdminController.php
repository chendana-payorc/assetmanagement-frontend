<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AdminController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function registerPost()
    {
    $name = $this->request->getPost('name');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $client = \Config\Services::curlrequest();

    try {
        $response = $client->post('http://localhost:3000/api/admin/register', [
            'form_params' => [
                'name' => $name,
                'email' => $email,
                'password' => $password
            ],
            'headers' => [
                'Accept' => 'application/json',
                'username' => env('API_USERNAME'),
                'password' => env('API_PASSWORD'),
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['success']) && $data['success']) {
            return redirect()->to('/login')->with('success', 'Registration successful! Please login.');
        } else {
            return redirect()->back()->with('error', 'Registration failed');
        }
        
    } catch (\Exception $e) {
        log_message('error', 'CURL error: '.$e->getMessage());
        return redirect()->back()->with('error', 'Server error: '.$e->getMessage());
    }
}

    public function login()
    {
        return view('login');
    }

    public function loginPost()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $client = \Config\Services::curlrequest();
        $apiUrl = 'http://localhost:3000/api/admin/login';

        try {
            $response = $client->post($apiUrl, [
                'json' => [
                    'email' => $email,
                    'password' => $password
                ],
                'headers' => [
                'Accept' => 'application/json',
                'username' => env('API_USERNAME'),
                'password' => env('API_PASSWORD'),
            ]
            ]);

            $result = json_decode($response->getBody(), true);

            if (isset($result['token'])) {
                session()->set('admin_token', $result['token']);
                return redirect()->to('/')->with('success', 'Login successful');
            } else {
                return redirect()->back()->with('error', 'Invalid credentials');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }


    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully');
    }
}
