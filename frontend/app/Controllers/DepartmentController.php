<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DepartmentController extends BaseController
{
    //  Updated index method with pagination (mirrors Asset module)
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

        //  Get filter values
        $name = $this->request->getGet('name');
        $status = $this->request->getGet('status');

        //  Build query string with filters AND pagination
        $queryParams = [
            'limit' => $limit,
            'offset' => $offset,
            'name' => $name,
            'status' => $status,
        ];

        // Remove empty parameters
        $queryParams = array_filter($queryParams, function($value) {
            return $value !== null && $value !== '';
        });

        $queryString = http_build_query($queryParams);

        // Get paginated data with filters
        $response = $client->get(
            getDepartmentApiUrl("/list?{$queryString}"),
            ['headers' => $headers]
        );
        
        $result = json_decode($response->getBody(), true);
        $departments = $result['data'] ?? [];

        // Total departments count from backend
        $totalDepartments = $result['totalCount'] ?? 0;

        // Pagination calculations
        $totalPages = $totalDepartments > 0 ? ceil($totalDepartments / $limit) : 1;
        $page = max(1, min($page, $totalPages));

        return view('frontend/department/department-index', array_merge(
            compact('departments', 'page', 'totalPages', 'name', 'status'),
            ['organizations' => $this->organizations]
        ));
    }

    //  Add filter method (same as Asset module)
    public function filterDepartment()
    {
        $client = getApiClient();
        $headers = getApiHeaders();

        $query = http_build_query([
            'name' => $this->request->getGet('name'),
            'status' => $this->request->getGet('status'),
        ]);

        try {
            $response = $client->get(getDepartmentApiUrl("/list?$query"), [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                "success" => false,
                "data" => [],
                "message" => "Unable to fetch departments"
            ]);
        }
    }

    public function store()
    {
        helper('api');

        $client = getApiClient();
        $headers = getApiHeaders();

        $name = $this->request->getPost('name');

        try {
            $response = $client->post(getDepartmentApiUrl('/create'), [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'Department created successfully',
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function update($id)
    {
        helper('api');

        $client = getApiClient();
        $headers = getApiHeaders();

        $name = $this->request->getPost('name');
        $status = $this->request->getPost('status');

        try {
            $response = $client->put(getDepartmentApiUrl('/update/' . $id), [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                    'status' => $status,
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'Department updated successfully',
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        helper('api');

        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $client->delete(getDepartmentApiUrl('/delete/' . $id), [
                'headers' => $headers,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Department deleted successfully',
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function editRecord()
    {
        helper('api');

        $encryptedId = $this->request->getPost('id');

        if (!$encryptedId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'error' => 'Department ID is required',
            ]);
        }

        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $response = $client->get(getDepartmentApiUrl('/get/' . $encryptedId), [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);
            $asset = $result['data'] ?? null;

            if (!$asset) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'error' => 'Department not found',
                ]);
            }

            return view('frontend/department/edit-form',array_merge(
                compact('asset'),
                ['organizations' => $this->organizations]
            ));
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to fetch department data: ' . $e->getMessage(),
            ]);
        }
    }
}
