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
        $client = getApiClient();
        $baseUrl = getApiBaseUrl();
        $headers = getApiHeaders();

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        try {
            $response = $client->post($baseUrl . '/register', [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                    'email' => $email,
                    'password' => $password
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (!empty($data['success'])) {
                return redirect()->to('/login')->with('success', 'Registration successful! Please login.');
            }

            return redirect()->back()->with('error', $data['message'] ?? 'Registration failed.');
        } catch (\Exception $e) {
            log_message('error', 'Register API error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function login()
    {
        return view('login');
    }

    public function loginPost()
    {
        $client = getApiClient();
        $baseUrl = getApiBaseUrl();
        $headers = getApiHeaders();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        try {
            // Step 1: Login API call
            $response = $client->post($baseUrl . '/login', [
                'headers' => $headers,
                'json' => [
                    'email' => $email,
                    'password' => $password
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            $token = $result['data']['token'] ?? $result['token'] ?? null;

            if ($token) {
                // Step 2: Store token and email in session
                session()->set('admin_token', $token);
                session()->set('admin_email', $email);

                // Step 3: Fetch all admins to get the name
                try {
                    $listResponse = $client->get($baseUrl . '/getAllAdmins', [
                        'headers' => array_merge($headers, [
                            'Authorization' => 'Bearer ' . $token,
                        ])
                    ]);

                    $listData = json_decode($listResponse->getBody(), true);
                    $admins = $listData['data'] ?? [];

                    foreach ($admins as $admin) {
                        if (!empty($admin['email']) && strtolower($admin['email']) === strtolower($email)) {
                            session()->set('admin_name', $admin['name'] ?? '');
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Admin list fetch failed: ' . $e->getMessage());
                }

                // Step 4: Redirect to dashboard
                return redirect()->to('/')->with('success', 'Login successful');
            }

            return redirect()->back()->with('error', $result['message'] ?? 'Invalid credentials');
        } catch (\Exception $e) {
            log_message('error', 'Login API error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        $token = session()->get('admin_token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        return view('frontend/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully');
    }
}
