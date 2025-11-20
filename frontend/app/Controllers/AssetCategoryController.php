<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AssetCategoryController extends Controller
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

        $response = $client->get(getAssetCategoryApiUrl('/list'), [
            'headers' => $headers,
            'query' => [
                'name' => $this->request->getGet('name'),
                'status' => $this->request->getGet('status')
            ]
        ]);     
        
        $result = json_decode($response->getBody(), true);
        $assetcategories = $result['data'] ?? [];
        return view('frontend/assetcategory/category-index', compact('assetcategories'));
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

            return view('frontend/assetcategory/edit-form', compact('assetcategory'));
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to fetch AssetCategory  data: ' . $e->getMessage(),
            ]);
        }
    }
}
