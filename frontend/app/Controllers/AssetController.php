<?php

namespace App\Controllers;

class AssetController extends BaseController
{
    private $apiBaseUrl;
    private $headers;

    public function __construct()
    {
        helper(['url', 'session']);
        $this->apiBaseUrl = env('API_BASE_URL') . '/asset';

        $this->headers = [
            'Accept' => 'application/json',
            'username' => env('API_USERNAME'),
            'password' => env('API_PASSWORD'),
        ];
    }

    public function index()
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
    
        $headers = [
            'Accept' => 'application/json',
            'username' => env('API_USERNAME'),
            'password' => env('API_PASSWORD'),
        ];
    
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
    
        $response = $client->get('http://localhost:3000/api/asset/list', [
            'headers' => $headers,
        ]);
    
        $result = json_decode($response->getBody(), true);
        $assets = $result['data'] ?? [];
    
        return view('frontend/asset-index', compact('assets'));
    }
    public function store()
    {
        $name = $this->request->getPost('name');
        $model = $this->request->getPost('model');
        $count = $this->request->getPost('count');
        $description = $this->request->getPost('description');
        
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
       
        $headers = $this->headers;
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        try {
            $response = $client->post($this->apiBaseUrl . '/create', [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                    'count'=>$count,
                    'model'=>$model,
                    'description'=>$description
                ]
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Asset created successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
   
    public function update($id)
    {
        $name = $this->request->getPost('name');
        $model = $this->request->getPost('model');
        $count = $this->request->getPost('count');
        $description = $this->request->getPost('description');

        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
        $headers = $this->headers;

        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        try {
            $client->put($this->apiBaseUrl . '/update/' . $id, [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                    'count'=>$count,
                    'model'=>$model,
                    'description'=>$description
                ]
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Asset updated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // DELETE Department
    public function delete($id)
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
        $headers = $this->headers;

        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        try {
            $client->delete($this->apiBaseUrl . '/delete/' . $id, [
                'headers' => $headers
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Asset deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}

