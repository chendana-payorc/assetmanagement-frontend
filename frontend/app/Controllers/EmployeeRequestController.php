<?php

namespace App\Controllers;

class EmployeeRequestController extends BaseController
{
    public function index()
    {
        //  CHECK SESSION
        if (!session()->get('employee_token')) {
            return redirect()->to('/employee-login')
                ->with('error', 'Session expired. Please log in again.');
        }

        helper('api');

        // Get page from URL
        $page = (int) $this->request->getGet('page');
        $page = $page > 0 ? $page : 1;

        // Fixed page size
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $token  = session()->get('employee_token');
        $employeeId = session()->get('employee_id');
        
        $client = getApiClient();
        $baseUrl = getEmployeeApiUrl();
        $headers = getApiHeaders();

        // Build query string with filters AND pagination
        $queryParams = [
            'limit' => $limit,
            'offset' => $offset,
            'asset_name' => $this->request->getGet('asset_name'),
            'status' => $this->request->getGet('status'),
        ];

        // Remove empty parameters
        $queryParams = array_filter($queryParams, function($value) {
            return $value !== null && $value !== '';
        });

        $queryString = http_build_query($queryParams);

        //  FETCH EMPLOYEE REQUESTS FROM YOUR NEW NODE API
        try {
            $response = $client->get(
                $baseUrl . '/asset-request/list?' . $queryString,
                [
                    'headers' => array_merge($headers, [
                        'Authorization' => 'Bearer ' . $token
                    ])
                ]
            );

            $result = json_decode($response->getBody(), true);
            $requests = $result['data'] ?? [];

            // Total requests count from backend
            $totalRequests = $result['totalCount'] ?? 0;

            // Pagination calculations
            $totalPages = $totalRequests > 0 ? ceil($totalRequests / $limit) : 1;
            $page = max(1, min($page, $totalPages));

        } catch (\Exception $e) {
            log_message('error', 'Employee Request Fetch Error: ' . $e->getMessage());
            $requests = [];
            $totalPages = 1;
        }

        // LOAD VIEW WITH employee_main.php LAYOUT
        return view(
            'employees/employeerequest/employee_request_index',
            array_merge(
                compact('requests', 'page', 'totalPages'),
                ['organizations' => $this->organizations]
            )
        );
    }

    public function delete($id)
{
    try {
        if (!session()->get('employee_token')) {
            return $this->response->setJSON([
                'error' => 'Session expired'
            ])->setStatusCode(401);
        }

        $token = session()->get('employee_token');
        $client = getApiClient();
        $baseUrl = getEmployeeApiUrl();
        $headers = getApiHeaders();  // THIS contains username + password

        // Call NODE API
        $response = $client->delete(
            $baseUrl . '/asset-request/delete/' . $id,
            [
                'headers' => array_merge($headers, [
                    'Authorization' => 'Bearer ' . $token
                ])
            ]
        );

        $result = json_decode($response->getBody(), true);
        return $this->response->setJSON($result);

    } catch (\Exception $e) {
        return $this->response->setJSON([
            'error' => $e->getMessage()
        ])->setStatusCode(500);
    }
}

public function getAssets()
{
    try {
        if (!session()->get('employee_token')) {
            return $this->response->setJSON(['error' => 'Session expired'])->setStatusCode(401);
        }

        $token   = session()->get('employee_token');
        $client  = getApiClient();
        $headers = getApiHeaders();

        // EMPLOYEE SHOULD USE SAME API AS ADMIN
        $url = getAssetApiUrl('/list');  // http://localhost:3000/api/asset/list

        $response = $client->get($url, [
            'headers' => array_merge($headers, [
                'Authorization' => 'Bearer ' . $token
            ])
        ]);

        $result = json_decode($response->getBody(), true);

        return $this->response->setJSON([
            'success' => true,
            'data'    => $result['data'] ?? []
        ]);

    } catch (\Exception $e) {
        return $this->response->setJSON([
            'error' => $e->getMessage()
        ])->setStatusCode(500);
    }
}

public function create()
{
    helper('api');

    // Check session for employee token
    $token = session()->get('employee_token');
    $employeeId = session()->get('employee_id');

    if (!$token || !$employeeId) {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Session expired. Please log in again.'
        ])->setStatusCode(401);
    }

    $client  = getApiClient();
    $headers = getEmployeeHeaders();

    // Get POST data
    $asset_id = $this->request->getPost('asset_id');
    $quantity = $this->request->getPost('requested_quantity') ?? $this->request->getPost('quantity');

    if (!$asset_id || !$quantity) {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Asset and quantity are required'
        ])->setStatusCode(400);
    }

    try {
        // Call Node API
        $response = $client->post(getEmployeeApiUrl('/asset-request/create'), [
            'headers' => $headers,
            'form_params' => [
                'employee_id'        => $employeeId,
                'asset_id'           => $asset_id,
                'requested_quantity' => $quantity,
            ],
        ]);

        $result = json_decode($response->getBody(), true);

        return $this->response->setJSON($result ?: [
            'success' => true,
            'message' => 'Request created successfully',
        ]);

    } catch (\Exception $e) {
        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'error' => $e->getMessage(),
        ]);
    }
}



}
