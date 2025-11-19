<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DesignationController extends Controller
{
    public function index()

    {
        $token = session()->get('admin_token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }
        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getDesignationApiUrl();


    $name   = $this->request->getGet('name');
    $status = $this->request->getGet('status');

    try {
        $response = $client->get($apiBaseUrl . '/list', [
            'headers' => $headers,
        ]);

        $result = json_decode($response->getBody(), true);
        $designations = $result['data'] ?? [];

        // Apply filters locally
        if (!empty($name)) {
            $designations = array_filter($designations, fn($d) => stripos($d['name'], $name) !== false);
        }
        if (!empty($status)) {
          //  $designations = array_filter($designations, fn($d) => strtolower($d['status']) == strtolower($status));

          $designations = array_filter($designations, function($d) use ($status) {
            return ($status == 'active'  && $d['status'] == 1) ||
                   ($status == 'inactive' && $d['status'] == 0);
        });
        
        }

        return view('frontend/designation/designation-index', compact('designations', 'name', 'status'));
    } catch (\Exception $e) {
        return $this->response->setStatusCode(500)->setBody('Error fetching designations: ' . $e->getMessage());
    }
}


    public function store()
    {
        $name = $this->request->getPost('name');

        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getDesignationApiUrl();

        try {
            $response = $client->post($apiBaseUrl . '/create', [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'Designation created successfully',
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to create designation: ' . $e->getMessage(),
            ]);
        }
    }

    public function update($id)
    {
        $name = $this->request->getPost('name');
        $status = $this->request->getPost('status');

        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getDesignationApiUrl();

        try {
            $response = $client->put($apiBaseUrl . '/update/' . $id, [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                    'status' => $status,
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'Designation updated successfully',
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to update designation: ' . $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getDesignationApiUrl();

        try {
            $client->delete($apiBaseUrl . '/delete/' . $id, [
                'headers' => $headers,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Designation deleted successfully',
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to delete designation: ' . $e->getMessage(),
            ]);
        }
    }

    public function editRecord()
    {
        $encryptedId = $this->request->getPost('id');

        if (!$encryptedId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'error' => 'Designation ID is required',
            ]);
        }

        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getDesignationApiUrl();

        try {
            $response = $client->get($apiBaseUrl . '/get/' . $encryptedId, [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);
            $asset = $result['data'] ?? null;

            if (!$asset) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'error' => 'Designation not found',
                ]);
            }

            return view('frontend/designation/edit-form', [
                'asset' => $asset,
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to fetch designation data: ' . $e->getMessage(),
            ]);
        }
    }
}
