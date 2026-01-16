<?php

namespace App\Controllers;
use Illuminate\Support\Facades\Log;
class AssetController extends BaseController
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

    // 🔹 Build query string with filters AND pagination
    $queryParams = [
        'limit' => $limit,
        'offset' => $offset,
        'asset_id' => $this->request->getGet('asset_id'),
        'model' => $this->request->getGet('model'),
        'name' => $this->request->getGet('name'),
        'count' => $this->request->getGet('count'),
        'price' => $this->request->getGet('price'),
        'category_id' => $this->request->getGet('category_id'),
        'supplier_id' => $this->request->getGet('supplier_id'),
    ];

    // Remove empty parameters
    $queryParams = array_filter($queryParams, function($value) {
        return $value !== null && $value !== '';
    });

    $queryString = http_build_query($queryParams);

    // Get paginated data with filters
    $response = $client->get(
        getAssetApiUrl("/list?{$queryString}"),
        ['headers' => $headers]
    );
    
    $result = json_decode($response->getBody(), true);
    $assets = $result['data'] ?? [];

    // Total assets count from backend
    $totalAssets = $result['totalCount'] ?? 0;

    // Pagination calculations
    $totalPages = $totalAssets > 0 ? ceil($totalAssets / $limit) : 1;
    $page = max(1, min($page, $totalPages));

    return view('frontend/asset/asset-index', array_merge(
        compact('assets', 'page', 'totalPages'),
        ['organizations' => $this->organizations]
    ));
}
    

public function filterAsset()
{
    $client  = getApiClient();
    $headers = getApiHeaders();
    $query = http_build_query([
        'asset_id'    => $this->request->getGet('asset_id'),
        'model'       => $this->request->getGet('model'),
        'name'        => $this->request->getGet('name'),
        'count'       => $this->request->getGet('count'),
        'price'       => $this->request->getGet('price'),
        'category_id' => $this->request->getGet('category_id'),
        'supplier_id' => $this->request->getGet('supplier_id'),
    ]);

    try {
       
        $response = $client->get(getAssetApiUrl("/list?$query"), [
            'headers' => $headers,
        ]);

        $result = json_decode($response->getBody(), true);

        return $this->response->setJSON($result);

    } catch (\Exception $e) {

        return $this->response->setJSON([
            "success" => false,
            "data"    => [],
            "message" => "Unable to fetch assets"
        ]);
    }
}

public function getCategory()
{
    $client = getApiClient();
    $headers = getApiHeaders();

    try {
        $response = $client->get('http://localhost:3000/api/assetcategory/list', [
            'headers' => $headers
        ]);

        $result = json_decode($response->getBody(), true);

        return $this->response->setJSON([
            'success' => true,
            'data' => $result['data'] ?? $result
        ]);

    } catch (\Exception $e) {
        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

public function getSupplier()
{
    $client = getApiClient();
    $headers = getApiHeaders();

    try {
        $response = $client->get('http://localhost:3000/api/supplier/list', [
            'headers' => $headers
        ]);

        $result = json_decode($response->getBody(), true);

        return $this->response->setJSON([
            'success' => true,
            'data' => $result['data'] ?? $result
        ]);

    } catch (\Exception $e) {
        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

public function store()
{
    helper('api');

    $client  = getApiClient();
    $headers = getApiHeaders();

    try {
        $response = $client->post(getAssetApiUrl('/create'), [
            'headers' => $headers,
            'form_params' => [
                'asset_id'          => $this->request->getPost('asset_id'),
                'model'             => $this->request->getPost('model'),
                'name'              => $this->request->getPost('name'),
                'count'             => $this->request->getPost('count'),
                'description'       => $this->request->getPost('description'),
                'price'             => $this->request->getPost('price'),
                'asset_categoryid'  => $this->request->getPost('category_id'),
                'asset_supplierid'  => $this->request->getPost('supplier_id'),
            ]
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Asset created successfully'
        ]);

    } catch (\Exception $e) {

        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'error'   => $e->getMessage()
        ]);
    }
}

public function update($id)
{
    helper('api');

    $client  = getApiClient();
    $headers = getApiHeaders();

    try {

        $response = $client->put(getAssetApiUrl('/update/' . $id), [
            'headers' => $headers,
            'form_params' => [
                'asset_id'          => $this->request->getPost('asset_id'),
                'model'             => $this->request->getPost('model'),
                'name'              => $this->request->getPost('name'),
                'count'             => $this->request->getPost('count'),
                'description'       => $this->request->getPost('description'),
                'price'             => $this->request->getPost('price'),
                'asset_categoryid'  => $this->request->getPost('category_id'),
                'asset_supplierid'  => $this->request->getPost('supplier_id'),
            ]
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Asset updated successfully'
        ]);

    } catch (\Exception $e) {

        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'error'   => $e->getMessage()
        ]);
    }
}


    public function delete($id)
    {
        helper('api');

        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $client->delete(getAssetApiUrl('/delete/' . $id), [
                'headers' => $headers,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Asset deleted successfully',
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function fetchAssetCategories(): array
    {
        $client = getApiClient();
        $headers = getApiHeaders();
    
        try {
            $response = $client->get(getAssetCategoryApiUrl('/list'), [
                'headers' => $headers,
            ]);
    
            $result = json_decode($response->getBody(), true);
            return $result['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function fetchAssetSuppliers(): array
    {
        $client = getApiClient();
        $headers = getApiHeaders();
    
        try {
            $response = $client->get(getSupplierApiUrl('/list'), [
                'headers' => $headers,
            ]);
    
            $result = json_decode($response->getBody(), true);
            return $result['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }
    
    public function editRecord()
    {
        $encryptedId = $this->request->getPost('id');
    
        if (!$encryptedId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'error' => 'Asset ID is required'
            ]);
        }
    
        $client = getApiClient();
        $headers = getApiHeaders();
    
        try {
            $response = $client->get(getAssetApiUrl('/get/' . $encryptedId), [
                'headers' => $headers
            ]);
    
            $result = json_decode($response->getBody(), true);
            $asset = $result['data'] ?? null;
    
            if (!$asset) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'error' => 'Asset not found'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to fetch asset data: ' . $e->getMessage()
            ]);
        }
    
        $categories = $this->fetchAssetCategories();
        $suppliers = $this->fetchAssetSuppliers();
    
        return view('frontend/asset/edit-form', array_merge(
            compact('asset', 'categories', 'suppliers'),
            ['organizations' => $this->organizations]
        ));
        
    }
    
}
