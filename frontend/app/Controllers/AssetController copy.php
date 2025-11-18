<?php

namespace App\Controllers;

class AssetController extends BaseController
{
   // use CodeIgniter\Database\Config;

    public function index()
{
    helper('api');

    // Connect to DB
    $db = \Config\Database::connect();  // ← notice the leading backslash
    $builder = $db->table('assets');    // replace 'assets' with your actual table name

    // Get filters from GET request
    $model = $this->request->getGet('model');
    $name  = $this->request->getGet('name');
    $count = $this->request->getGet('count');

    // Apply filters only if provided
    if (!empty($model)) {
        $builder->like('model', $model);
    }

    if (!empty($name)) {
        $builder->like('name', $name);
    }

    if (!empty($count)) {
        $builder->where('count', $count);
    }

    // Get the results
    $assets = $builder->get()->getResultArray(); // ← use getResultArray() for array

    return view('frontend/asset/asset-index', [
        'assets' => $assets,
        'filters' => [
            'model' => $model,
            'name'  => $name,
            'count' => $count
        ]
    ]);
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
