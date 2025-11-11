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
            // Step 1: Login API call
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
    
            // Extract token from nested "data" if exists
            $token = $result['data']['token'] ?? $result['token'] ?? null;
    
            if ($token) {
                // Step 2: Store token and email in session
                session()->set('admin_token', $token);
                session()->set('admin_email', $email);
    
                // Step 3: Optionally fetch all admins to get admin name
                try {
                    $listResponse = $client->get('http://localhost:3000/api/admin/getAllAdmins', [
                        'headers' => [
                            'Accept' => 'application/json',
                            'username' => env('API_USERNAME'),
                            'password' => env('API_PASSWORD'),
                            'Authorization' => 'Bearer ' . $token,
                        ]
                    ]);
    
                    $listData = json_decode($listResponse->getBody(), true);
                    $admins = $listData['data'] ?? [];
    
                    // Find the logged-in admin by email
                    foreach ($admins as $admin) {
                        if (!empty($admin['email']) && strtolower($admin['email']) === strtolower($email)) {
                            session()->set('admin_name', $admin['name'] ?? '');
                            break;
                        }
                    }
    
                } catch (\Exception $e) {
                    // Optional: name fetching is not critical
                    log_message('error', 'Error fetching admin list: ' . $e->getMessage());
                }
    
                // Step 4: Redirect to home
                return redirect()->to('/')->with('success', 'Login successful');
            }
    
            // If token not found
            return redirect()->back()->with('error', $result['message'] ?? 'Invalid credentials');
    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }
    

    public function dashboard()
    {
        $token = session()->get('admin_token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Please Login First');
        } 
        return view('frontend/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully');
    }
}
