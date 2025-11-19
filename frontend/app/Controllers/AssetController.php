<?php

namespace App\Controllers;

class AssetController extends BaseController
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

    $model = $this->request->getGet('model');
    $name  = $this->request->getGet('name');
    $count = $this->request->getGet('count');

    // Fetch assets from API
    $response = $client->get(getAssetApiUrl('/list'), [
        'headers' => $headers,
    ]);

    $result = json_decode($response->getBody(), true);
    $assets = $result['data'] ?? [];

    // Apply filtering locally
    if (!empty($model)) {
        $assets = array_filter($assets, fn($a) => stripos($a['model'], $model) !== false);
    }
    if (!empty($name)) {
        $assets = array_filter($assets, fn($a) => stripos($a['name'], $name) !== false);
    }
    if (!empty($count)) {
        $assets = array_filter($assets, fn($a) => $a['count'] == $count);
    }

    return view('frontend/asset/asset-index', compact('assets', 'model', 'name', 'count'));
}



    public function store()
    {
        helper('api');

        $client = getApiClient();
        $headers = getApiHeaders();

        $name = $this->request->getPost('name');
        $model = $this->request->getPost('model');
        $count = $this->request->getPost('count');
        $description = $this->request->getPost('description');

        try {
            $client->post(getAssetApiUrl('/create'), [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                    'model' => $model,
                    'count' => $count,
                    'description' => $description,
                ],
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Asset created successfully',
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
        $model = $this->request->getPost('model');
        $count = $this->request->getPost('count');
        $description = $this->request->getPost('description');

        try {
            $client->put(getAssetApiUrl('/update/' . $id), [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                    'model' => $model,
                    'count' => $count,
                    'description' => $description,
                ],
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Asset updated successfully',
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

    public function editRecord()
    {
        helper('api');

        $encryptedId = $this->request->getPost('id');
        if (!$encryptedId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'error' => 'Asset ID is required',
            ]);
        }

        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $response = $client->get(getAssetApiUrl('/get/' . $encryptedId), [
                'headers' => $headers,
            ]);
            $result = json_decode($response->getBody(), true);
            $asset = $result['data'] ?? null;

            if (!$asset) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'error' => 'Asset not found',
                ]);
            }

            return view('frontend/asset/edit-form', compact('asset'));
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to fetch asset data: ' . $e->getMessage(),
            ]);
        }
    }
}
