<?php

namespace App\Controllers;

class EmployeePanelController extends BaseController
{
    // show login view
    public function login()
    {
        return view('employees/login', [
            'organizations' => $this->organizations
        ]);
    }

    // process login
    public function loginPost()
{
    $client = getApiClient();
    $baseUrl = getEmployeeApiUrl(); // uses same helper as admin EmployeeController
    $headers = getApiHeaders();

    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    try {
        $response = $client->post($baseUrl . '/login', [
            'headers' => $headers,
            'json' => [
                'email' => $email,
                'password' => $password
            ]
        ]);

        $result = json_decode($response->getBody(), true);

        if (empty($result['success'])) {
            return redirect()->back()->with('error', $result['message'] ?? 'Invalid credentials');
        }

        $token = $result['data']['token'] ?? null;
        $name  = $result['data']['name'] ?? '';

        if (!$token) {
            return redirect()->back()->with('error', 'Token not returned from API');
        }

        // store token and employee name in session
        session()->set('employee_token', $token);
        session()->set('employee_name', $name);
        session()->set('employee_email', $email);

        // --- NEW CODE: decode JWT to get employee_id ---
        $payload = explode('.', $token)[1] ?? '';
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payload)), true);
        $employeeId = $payload['data']['id'] ?? null;
        session()->set('employee_id', $employeeId);

        return redirect()->to('/employee-dashboard')->with('success', 'Login successful');

    } catch (\Exception $e) {
        log_message('error', 'Employee Login Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
    }
}


    // logout
    public function logout()
    {
        session()->remove(['employee_token', 'employee_email', 'employee_name']);
        return redirect()->to('/employee-login')->with('success', 'Logged out');
    }

    // employee dashboard (simple summary / welcome)
    public function dashboard()
{
    if (!session()->get('employee_token')) {
        return redirect()->to('/employee-login');
    }

    $client = getApiClient();
    $baseUrl = getApiBaseUrl();
    $headers = getApiHeaders();

    $employeeId = session()->get('employee_id');

    try {
        $res = $client->get($baseUrl . "/assetrequest?employee_id=" . $employeeId, [
            "headers" => $headers
        ]);

        $result = json_decode($res->getBody(), true);
        $requests = $result['data'] ?? [];

    } catch (\Exception $e) {
        $requests = [];
    }

    return view('employees/dashboard', compact('requests'));
}


    // request asset page (shows available assets)
    public function requestAsset()
    {
        if (!session()->get('employee_token')) return redirect()->to('/employee-login');

        $client = getApiClient();
        $baseUrl = getApiBaseUrl(); // reuse main API base for asset endpoints
        $headers = getApiHeaders();
        $token = session()->get('employee_token');

        try {
            // try asset list endpoint
            $response = $client->get($baseUrl . '/asset/list', [
                'headers' => array_merge($headers, ['Authorization' => 'Bearer ' . $token])
            ]);

            $result = json_decode($response->getBody(), true);
            $assets = $result['data'] ?? [];

        } catch (\Exception $e) {
            $assets = [];
            log_message('error', 'Asset list fetch failed: ' . $e->getMessage());
        }

        return view('frontend/employee/request_asset', array_merge(
            compact('assets'),
            ['organizations' => $this->organizations]
        ));
    }

    // submit request to asset request API
    public function submitRequest()
    {
        if (!session()->get('employee_token')) return redirect()->to('/employee-login');

        $client = getApiClient();
        $baseUrl = getApiBaseUrl();
        $headers = getApiHeaders();
        $token = session()->get('employee_token');

        $asset_id = $this->request->getPost('asset_id');
        $note = $this->request->getPost('note');

        try {
            $response = $client->post($baseUrl . '/assetrequest/create', [
                'headers' => array_merge($headers, ['Authorization' => 'Bearer ' . $token]),
                'form_params' => [
                    'asset_id' => $asset_id,
                    'note' => $note
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            if (!empty($result['success'])) {
                return redirect()->to('/employee-my-requests')->with('success', 'Request submitted');
            }

            return redirect()->back()->with('error', $result['message'] ?? 'Request failed');

        } catch (\Exception $e) {
            log_message('error', 'SubmitRequest error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    // list of my requests
    public function myRequests()
    {
        if (!session()->get('employee_token')) return redirect()->to('/employee-login');

        $client = getApiClient();
        $baseUrl = getApiBaseUrl();
        $headers = getApiHeaders();
        $token = session()->get('employee_token');

        try {
            $response = $client->get($baseUrl . '/employee/requests', [
                'headers' => array_merge($headers, ['Authorization' => 'Bearer ' . $token])
            ]);

            $result = json_decode($response->getBody(), true);
            $requests = $result['data'] ?? [];

        } catch (\Exception $e) {
            $requests = [];
            log_message('error', 'MyRequests fetch failed: ' . $e->getMessage());
        }

        return view('frontend/employee/my_requests', array_merge(
            compact('requests'),
            ['organizations' => $this->organizations]
        ));
    }

    // assigned assets for this employee
    public function assignedAssets()
    {
        if (!session()->get('employee_token')) return redirect()->to('/employee-login');

        $client = getApiClient();
        $baseUrl = getApiBaseUrl();
        $headers = getApiHeaders();
        $token = session()->get('employee_token');

        try {
            $response = $client->get($baseUrl . '/employee/assigned-assets', [
                'headers' => array_merge($headers, ['Authorization' => 'Bearer ' . $token])
            ]);

            $result = json_decode($response->getBody(), true);
            $assets = $result['data'] ?? [];

        } catch (\Exception $e) {
            $assets = [];
            log_message('error', 'AssignedAssets fetch failed: ' . $e->getMessage());
        }

        return view('frontend/employee/assigned_assets', array_merge(
            compact('assets'),
            ['organizations' => $this->organizations]
        ));
    }
}
