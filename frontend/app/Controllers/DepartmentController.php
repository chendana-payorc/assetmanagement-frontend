<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DepartmentController extends Controller
{
    public function index()
    {
        $token = session()->get('admin_token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }
        helper('api');

        $client = getApiClient();
        $headers = getApiHeaders();

        $response = $client->get(getDepartmentApiUrl('/list'), [
            'headers' => $headers,
        ]);

        $result = json_decode($response->getBody(), true);
        $departments = $result['data'] ?? [];

        return view('frontend/department/department-index', compact('departments'));
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

            return view('frontend/department/edit-form', compact('asset'));
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to fetch department data: ' . $e->getMessage(),
            ]);
        }
    }
}
