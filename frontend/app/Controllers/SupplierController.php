<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SupplierController extends Controller
{
    public function index()
    {
        $token = session()->get('admin_token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getSupplierApiUrl();

        // ====== GET FILTER INPUTS ======
        $name              = $this->request->getGet('name');
        $email             = $this->request->getGet('email');
        $phone             = $this->request->getGet('phone');
        $organization_name = $this->request->getGet('organization_name');
        $address           = $this->request->getGet('address');
        $status            = $this->request->getGet('status');

        try {
            $response = $client->get($apiBaseUrl . '/list', [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);
            $suppliers = $result['data'] ?? [];

            // ====== APPLY FILTERS ======

            // Filter name
            if (!empty($name)) {
                $suppliers = array_filter($suppliers, fn($s) =>
                    stripos($s['supplier_name'], $name) !== false
                );
            }

            // Filter email
            if (!empty($email)) {
                $suppliers = array_filter($suppliers, fn($s) =>
                    stripos($s['email'], $email) !== false
                );
            }

            // Filter phone
            if (!empty($phone)) {
                $suppliers = array_filter($suppliers, fn($s) =>
                    stripos($s['phone'], $phone) !== false
                );
            }

            // Filter organization
            if (!empty($organization_name)) {
                $suppliers = array_filter($suppliers, fn($s) =>
                    stripos($s['organization_name'], $organization_name) !== false
                );
            }

            // Filter address
            if (!empty($address)) {
                $suppliers = array_filter($suppliers, fn($s) =>
                    stripos($s['address'], $address) !== false
                );
            }

            // Filter status
            if (!empty($status)) {
                $suppliers = array_filter($suppliers, fn($s) =>
                    strtolower($s['status']) === strtolower($status)
                );
            }

            return view('frontend/supplier/supplier-index', [
                'suppliers'        => $suppliers,
                'name'             => $name,
                'email'            => $email,
                'phone'            => $phone,
                'organization_name'=> $organization_name,
                'address'          => $address,
                'status'           => $status,
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setBody('Error fetching supplier list: ' . $e->getMessage());
        }
    }

    // ==================== STORE ====================
    public function store()
    {
        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getSupplierApiUrl();

        try {
            $response = $client->post($apiBaseUrl . '/create', [
                'headers' => $headers,
                'form_params' => [
                    'supplier_name'     => $this->request->getPost('supplier_name'),
                    'email'             => $this->request->getPost('email'),
                    'phone'             => $this->request->getPost('phone'),
                    'organization_name' => $this->request->getPost('organization_name'),
                    'address'           => $this->request->getPost('address'),
                    'status'            => $this->request->getPost('status'),
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

    // ==================== UPDATE ====================
    public function update($id)
    {
        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getSupplierApiUrl();

        try {
            $response = $client->put($apiBaseUrl . '/update/' . $id, [
                'headers' => $headers,
                'form_params' => [
                    'supplier_name'     => $this->request->getPost('supplier_name'),
                    'email'             => $this->request->getPost('email'),
                    'phone'             => $this->request->getPost('phone'),
                    'organization_name' => $this->request->getPost('organization_name'),
                    'address'           => $this->request->getPost('address'),
                    'status'            => $this->request->getPost('status'),
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

    // ==================== DELETE ====================
    public function delete($id)
    {
        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getSupplierApiUrl();

        try {
            $client->delete($apiBaseUrl . '/delete/' . $id, [
                'headers' => $headers,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Supplier deleted successfully'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function editRecord()
    {
        $encryptedId = $this->request->getPost('id');

        if (!$encryptedId) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Supplier ID missing'
            ]);
        }

        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getSupplierApiUrl();

        try {
            $response = $client->get($apiBaseUrl . '/get/' . $encryptedId, [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);

            return view('frontend/supplier/edit-form', [
                'supplier' => $result['data']
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
