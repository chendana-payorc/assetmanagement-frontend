<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AssetAssignmentController extends BaseController
{
    public function index()
    {
        $token = session()->get('admin_token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        helper('api');

        // Get page from URL
        $page = (int) $this->request->getGet('page');
        $page = $page > 0 ? $page : 1;

        // Fixed page size
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getAssetAssignmentApiUrl();

        // Build query string with filters AND pagination
        $queryParams = [
            'limit' => $limit,
            'offset' => $offset,
            'asset_name' => $this->request->getGet('asset_name'),
            'model' => $this->request->getGet('model'),
            'employee_name' => $this->request->getGet('employee_name'),
            'assigned_quantity' => $this->request->getGet('assigned_quantity'),
            'assigned_date' => $this->request->getGet('assigned_date'),
            'status' => $this->request->getGet('status'),
        ];

        // Remove empty parameters
        $queryParams = array_filter($queryParams, function($value) {
            return $value !== null && $value !== '';
        });

        $queryString = http_build_query($queryParams);

        try {
            // Get paginated data with filters
            $response = $client->get($apiBaseUrl . '/list?' . $queryString, [
                'headers' => $headers,
            ]);
            
            $result = json_decode($response->getBody(), true);
            $assignments = $result['data'] ?? [];

            // Format dates
            foreach ($assignments as &$a) {
                if (!empty($a['assigned_date'])) {
                    $a['assigned_date'] = date('d-m-Y H:i:s', strtotime($a['assigned_date']));
                }
                if (!empty($a['return_date'])) {
                    $a['return_date'] = date('d-m-Y H:i:s', strtotime($a['return_date']));
                }
            }

            // Total assignments count from backend
            $totalAssignments = $result['totalCount'] ?? 0;

            // Pagination calculations
            $totalPages = $totalAssignments > 0 ? ceil($totalAssignments / $limit) : 1;
            $page = max(1, min($page, $totalPages));

            return view('frontend/assetassignment/assetassignment-index', array_merge(
                compact('assignments', 'page', 'totalPages'),
                ['organizations' => $this->organizations]
            ));

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setBody('Error fetching assignment list: ' . $e->getMessage());
        }
    }

    // STORE (assign)
    public function store()
    {
        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getAssetAssignmentApiUrl();

        try {
            $response = $client->post($apiBaseUrl . '/assign', [
                'headers' => $headers,
                'form_params' => [
                    'asset_id' => $this->request->getPost('asset_id'),
                    'employee_id' => $this->request->getPost('employee_id'),
                    'assigned_quantity' => $this->request->getPost('assigned_quantity'),
                    'handover_person' => $this->request->getPost('handover_person'),
                ],
            ]);

            return $this->response->setJSON(json_decode($response->getBody(), true));

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // RETURN (called from frontend via PUT)
    public function returnAsset($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Assignment ID missing'
            ]);
        }

        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getAssetAssignmentApiUrl();

        try {
            $response = $client->put($apiBaseUrl . '/return/' . $id, [
                'headers' => $headers,
            ]);

            // backend generally returns success object — forward it
            return $this->response->setJSON(json_decode($response->getBody(), true));

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Utility endpoints for populating selects (assets and employees)
    // You can wire these routes in frontend routes to be called by JS: asset-list-json, employee-list-json
    // Below methods return simplified arrays (id, name, model etc.)
    public function assetListJson()
    {
        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getAssetApiUrl();

        try {
            $response = $client->get($apiBaseUrl . '/list', ['headers' => $headers]);
            $result = json_decode($response->getBody(), true);
            $assets = $result['data'] ?? [];

            // map to id,name,model — asset id should be encrypted as backend returns
            $list = array_map(fn($a) => [
                'id' => $a['id'],
                'name' => $a['name'] ?? ($a['asset_name'] ?? ''),
                'model' => $a['model'] ?? ''
            ], $assets);

            return $this->response->setJSON($list);

        } catch (\Exception $e) {
            return $this->response->setJSON([]);
        }
    }

    public function employeeListJson()
    {
        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getEmployeeApiUrl();

        try {
            $response = $client->get($apiBaseUrl . '/list', ['headers' => $headers]);
            $result = json_decode($response->getBody(), true);
            $emps = $result['data'] ?? [];

            // map to id (employee identifier), name, email
            $list = array_map(fn($e) => [
                'id' => $e['id'] ?? ($e['employee_id'] ?? ''),
                'name' => $e['name'] ?? '',
                'email' => $e['email'] ?? ''
            ], $emps);

            return $this->response->setJSON($list);

        } catch (\Exception $e) {
            return $this->response->setJSON([]);
        }
    }

    // DELETE assignment
public function delete($id = null)
{
    if (!$id) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Assignment ID missing'
        ]);
    }

    $client = getApiClient();
    $headers = getApiHeaders();
    $apiBaseUrl = getAssetAssignmentApiUrl();

    try {
        $response = $client->delete($apiBaseUrl . '/delete/' . $id, [
            'headers' => $headers
        ]);

        return $this->response->setJSON(json_decode($response->getBody(), true));

    } catch (\Exception $e) {
        return $this->response->setJSON([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// GET assignment details for editing
public function edit($id = null)
{
    if (!$id) {
        return $this->response->setJSON(['success' => false, 'message' => 'Assignment ID missing']);
    }

    $client = getApiClient();
    $headers = getApiHeaders();
    $apiBaseUrl = getAssetAssignmentApiUrl();

    try {
        // Fetch assignment by ID
        $response = $client->get($apiBaseUrl . '/get/' . $id, [
            'headers' => $headers
        ]);

        $result = json_decode($response->getBody(), true);

        if (empty($result['data'])) {
            return "No data found";
        }

        $assignment = $result['data'];

        // Fetch assets
        $assetResponse = $client->get(getAssetApiUrl() . "/list", [
            'headers' => $headers
        ]);
        $assets = json_decode($assetResponse->getBody(), true)['data'] ?? [];

        // Fetch employees
        $empResponse = $client->get(getEmployeeApiUrl() . "/list", [
            'headers' => $headers
        ]);
        $employees = json_decode($empResponse->getBody(), true)['data'] ?? [];

        return view('frontend/assetassignment/edit-form', [
            'assignment' => $assignment,
            'assets' => $assets,
            'employees' => $employees,
            'organizations' => $this->organizations
        ]);

    } catch (\Exception $e) {
        return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
    }
}


public function update($id = null)
{
    if (!$id) {
        return $this->response->setJSON(['success' => false, 'message' => 'Assignment ID missing']);
    }

    $client = getApiClient();
    $headers = getApiHeaders();
    $apiBaseUrl = getAssetAssignmentApiUrl();

    // Backend accepts update using POST /assign
    $data = [
        'assign_id' => $id,
        'assigned_quantity' => $this->request->getPost('assigned_quantity'),
        'handover_person' => $this->request->getPost('handover_person'),
        'asset_id' => $this->request->getPost('asset_id'),
        'employee_id' => $this->request->getPost('employee_id'),
    ];

    try {
        $response = $client->post($apiBaseUrl . '/assign', [
            'headers' => $headers,
            'form_params' => $data
        ]);

        return $this->response->setJSON(json_decode($response->getBody(), true));

    } catch (\Exception $e) {
        return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
    }
}


}
