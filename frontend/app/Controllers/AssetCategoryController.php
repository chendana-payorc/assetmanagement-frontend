<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AssetCategoryController extends BaseController
{
    public function index()
    {
        $token = session()->get('admin_token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }
        helper('api');

        // 🔹 Get page from URL
        $page = (int) $this->request->getGet('page');
        $page = $page > 0 ? $page : 1;

        // 🔹 Fixed page size
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $client = getApiClient();
        $headers = getApiHeaders();

        $name = $this->request->getGet('name');
    $status = $this->request->getGet('status');

        // 🔹 Build query string with filters AND pagination
        $queryParams = [
            'limit' => $limit,
            'offset' => $offset,
            'name' => $this->request->getGet('name'),
            'status' => $this->request->getGet('status'),
        ];

        // Remove empty parameters
        $queryParams = array_filter($queryParams, function($value) {
            return $value !== null && $value !== '';
        });

        $queryString = http_build_query($queryParams);

        // 🔹 Get paginated data with filters
        $response = $client->get(
            getAssetCategoryApiUrl("/list?{$queryString}"),
            ['headers' => $headers]
        );
        
        $result = json_decode($response->getBody(), true);
        $assetcategories = $result['data'] ?? [];

        // 🔹 Total categories count from backend
        $totalCategories = $result['totalCount'] ?? 0;

        // 🔹 Pagination calculations
        $totalPages = $totalCategories > 0 ? ceil($totalCategories / $limit) : 1;
        $page = max(1, min($page, $totalPages));

        return view('frontend/assetcategory/category-index', array_merge(
            compact('assetcategories', 'page', 'totalPages'),
            ['organizations' => $this->organizations]
        ));
    }

    // 🔹 Add filter method (same as Asset module)
    public function filterCategory()
    {
        $client = getApiClient();
        $headers = getApiHeaders();
        $query = http_build_query([
            'name' => $this->request->getGet('name'),
            'status' => $this->request->getGet('status'),
        ]);

        try {
            $response = $client->get(getAssetCategoryApiUrl("/list?$query"), [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                "success" => false,
                "data" => [],
                "message" => "Unable to fetch asset categories"
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
            $response = $client->post(getAssetCategoryApiUrl('/create'), [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'AssetCategory created successfully',
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
            $response = $client->put(getAssetCategoryApiUrl('/update/' . $id), [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                    'status' => $status,
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'AssetCategory updated successfully',
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
            $client->delete(getAssetCategoryApiUrl('/delete/' . $id), [
                'headers' => $headers,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'AssetCategory deleted successfully',
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
                'error' => 'AssetCategory ID is required',
            ]);
        }

        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $response = $client->get(getAssetCategoryApiUrl('/get/' . $encryptedId), [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);
            $assetcategory = $result['data'] ?? null;

            if (!$assetcategory) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'error' => 'AssetCategory not found',
                ]);
            }

            return view('frontend/assetcategory/edit-form', array_merge(
                compact('assetcategory'),
                ['organizations' => $this->organizations]
            ));
            
            
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to fetch AssetCategory  data: ' . $e->getMessage(),
            ]);
        }
    }
}
