<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class UserController extends Controller
{
    private $apiBaseUrl;
    private $headers;

    public function __construct()
    {
        helper(['url', 'session']);
        $this->apiBaseUrl = env('API_BASE_URL') . '/admin';

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
    
        $response = $client->get($this->apiBaseUrl . '/list', [
            'headers' => $headers,
        ]);
    
        $result = json_decode($response->getBody(), true);
        $users = $result['data'] ?? []; 
    
        return view('frontend/users-index', compact('users'));
    }
   

    public function getDesignations()
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
        $headers = $this->headers;
    
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
    
        try {
            $response = $client->get('http://localhost:3000/api/designation/list', [
                'headers' => $headers
            ]);
    
            return $this->response->setJSON(json_decode($response->getBody(), true));
    
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function getDepartments()
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
        $headers = $this->headers;
    
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
    
        try {
            $response = $client->get('http://localhost:3000/api/department/list', [
                'headers' => $headers
            ]);
    
            return $this->response->setJSON(json_decode($response->getBody(), true));
    
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    

}