<?php
 
namespace App\Controllers;
 
use CodeIgniter\Controller;
 
class SupplierController extends BaseController
{
    public function index()
    {
        $token = session()->get('admin_token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        //  Get page from URL
        $page = (int) $this->request->getGet('page');
        $page = $page > 0 ? $page : 1;

        //  Fixed page size
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getSupplierApiUrl();

        //  Get filter values
        $name = $this->request->getGet('name');
        $email = $this->request->getGet('email');
        $phone = $this->request->getGet('phone');
        $organization_name = $this->request->getGet('organization_name');
        $address = $this->request->getGet('address');
        $status = $this->request->getGet('status');

        //  Build query string with filters AND pagination
        $queryParams = [
            'limit' => $limit,
            'offset' => $offset,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'organization_name' => $organization_name,
            'address' => $address,
            'status' => $status,
        ];

        // Remove empty parameters
        $queryParams = array_filter($queryParams, function($value) {
            return $value !== null && $value !== '';
        });

        $queryString = http_build_query($queryParams);

        try {
            //  Get paginated data with filters
            $response = $client->get(
                $apiBaseUrl . "/list?{$queryString}",
                ['headers' => $headers]
            );
 
            $result = json_decode($response->getBody(), true);
            $suppliers = $result['data'] ?? [];

            //  Total suppliers count from backend
            $totalSuppliers = $result['totalCount'] ?? 0;

            //  Pagination calculations
            $totalPages = $totalSuppliers > 0 ? ceil($totalSuppliers / $limit) : 1;
            $page = max(1, min($page, $totalPages));
 
            return view('frontend/supplier/supplier-index', [
                'suppliers' => $suppliers,
                'page' => $page,
                'totalPages' => $totalPages,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'organization_name' => $organization_name,
                'address' => $address,
                'status' => $status,
                'organizations' => $this->organizations
            ]);
 
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setBody('Error fetching supplier list: ' . $e->getMessage());
        }
    }

    //  Add filter method (same as Asset module)
    public function filterSupplier()
    {
        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = getSupplierApiUrl();

        $query = http_build_query([
            'name' => $this->request->getGet('name'),
            'email' => $this->request->getGet('email'),
            'phone' => $this->request->getGet('phone'),
            'organization_name' => $this->request->getGet('organization_name'),
            'address' => $this->request->getGet('address'),
            'status' => $this->request->getGet('status'),
        ]);

        try {
            $response = $client->get($apiBaseUrl . "/list?$query", [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                "success" => false,
                "data" => [],
                "message" => "Unable to fetch suppliers"
            ]);
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
                'supplier' => $result['data'],
                'organizations' => $this->organizations
            ]);
 
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
 
 