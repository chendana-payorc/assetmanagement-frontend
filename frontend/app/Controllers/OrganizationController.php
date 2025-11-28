<?php
 
namespace App\Controllers;
 
use CodeIgniter\Controller;
 
class OrganizationController extends Controller
{
    public function index()
    {
        $token = session()->get('admin_token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }
 
        $client = getApiClient();
        $headers = getApiHeaders();
 
        $apiBaseUrl = 'http://localhost:3000/api/organization';
        try {
            $response = $client->get($apiBaseUrl . '/list', [
                'headers' => $headers,
            ]);
 
            $result = json_decode($response->getBody(), true);
            $organizations = $result['data'] ?? [];

 
            return view('frontend/organization/organization-index', compact(
                'organizations',
            ));
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setBody('Error fetching organizations: ' . $e->getMessage());
        }
    }
   
    public function store()
    {
        $apiUrl = 'http://localhost:3000/api/organization/create';
        $headers = getApiHeaders(); 
    
        $postFields = [
            'id'         => $this->request->getPost('id'),
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'contact_no' => $this->request->getPost('contact_no'),
            'address'    => $this->request->getPost('address'),
            'country'    => $this->request->getPost('country'),
            'state'      => $this->request->getPost('state'),
            'city'       => $this->request->getPost('city'),
            'zipcode'    => $this->request->getPost('zipcode'),
            'username'   => $this->request->getPost('username'),
            'password'   => $this->request->getPost('password'),
        ];
    
        foreach (['logo','favicon'] as $fileField) {
            $file = $this->request->getFile($fileField);
            if ($file && $file->isValid() && $file->getSize() > 0) {
                $postFields[$fileField] = curl_file_create(
                    $file->getRealPath(),
                    $file->getClientMimeType(),
                    $file->getClientName()
                );
            }
        }
    
        $httpHeaders = [];
        foreach ($headers as $key => $value) {
            $httpHeaders[] = $key . ': ' . $value;
        }
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders); // ✅ send headers
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        return $this->response->setJSON(json_decode($response, true));
    }
  
}
 