<?php

namespace App\Controllers;

use CodeIgniter\Controller;
class DesignationController extends Controller
{
    private $apiBaseUrl;
    private $headers;

    public function __construct()
    {
        helper(['url', 'session']);
        $this->apiBaseUrl = env('API_BASE_URL') . '/designation';

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
        $designations = $result['data'] ?? []; 
    
        return view('frontend/designation/designation-index', compact('designations'));
    }
    

    public function store()
    {
        $name = $this->request->getPost('name');
        
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
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'Designation created successfully'
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
        //log_message('debug', '🟦 ID: ' . $id);
        $name = $this->request->getPost('name');
        $status = $this->request->getPost('status');

        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
        $headers = $this->headers;

        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        try {
           $response= $client->put($this->apiBaseUrl . '/update/' . $id, [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                    'status' => $status
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'Designation updated successfully'
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
                 'message' => 'Designation deleted successfully'
             ]);
         } catch (\Exception $e) {
             return $this->response->setStatusCode(500)->setJSON([
                 'success' => false,
                 'error' => $e->getMessage()
             ]);
         }
     }

     public function editRecord()
     {
        $encryptedId = $this->request->getPost('id');
        
         if (!$encryptedId) {
             return $this->response->setStatusCode(400)->setJSON([
                 'success' => false,
                 'error' => 'Designation ID is required'
             ]);
         }
     
         $client = \Config\Services::curlrequest();
         $token = session()->get('admin_token');
         $headers = $this->headers;
     
         if ($token) {
             $headers['Authorization'] = 'Bearer ' . $token;
         }
     
         try {
            $apiUrl = $this->apiBaseUrl . '/get/' . $encryptedId;
            //log_message('debug', '🟦 API URL: ' . $apiUrl);
            
             $response = $client->get($apiUrl, ['headers' => $headers]);
             $result = json_decode($response->getBody(), true);
             $asset = $result['data'] ?? null;
     
             if (!$asset) {
                 return $this->response->setStatusCode(404)->setJSON([
                     'success' => false,
                     'error' => 'Designation not found'
                 ]);
             }
     
         } catch (\Exception $e) {
             return $this->response->setStatusCode(500)->setJSON([
                 'success' => false,
                 'error' => 'Failed to fetch designation data: ' . $e->getMessage()
             ]);
         }
     
     
         return view('frontend/designation/edit-form', [
             'asset' => $asset,
             
         ]);
     }
}
