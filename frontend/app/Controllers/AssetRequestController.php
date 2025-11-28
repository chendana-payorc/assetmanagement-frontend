<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AssetRequestController extends BaseController
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

        $response = $client->get(getAssetRequestApiUrl('/list'), [
            'headers' => $headers,
            'query' => [
                'employee_name' => $this->request->getGet('employee_name'),
                'asset_name' => $this->request->getGet('asset_name'),
                'status' => $this->request->getGet('status')
            ]
        ]);

        $result = json_decode($response->getBody(), true);
        $requests = $result['data'] ?? [];

        return view('frontend/assetRequest/request-index', array_merge(
            compact('requests'),
            ['organizations' => $this->organizations]
        ));
        
    }

    
    public function getAsset()
    {
        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $response = $client->get('http://localhost:3000/api/asset/list', [
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

    // Log incoming POST data
    log_message('info', 'Incoming Request Data: ' . json_encode($this->request->getPost()));

    try {

        $response = $client->post(getAssetRequestApiUrl('/create'), [
            'headers' => $headers,
            'form_params' => [
                'employee_id'        => "cXQxV0RpNllHRWZJTENKTlNzU3g1QT09",
                'asset_id'           => $this->request->getPost('asset_id'),
                'requested_quantity' => $this->request->getPost('quantity'),
            ],
        ]);

        $result = json_decode($response->getBody(), true);

        // Log API response
        //log_message('info', 'API Response: ' . json_encode($result));

        return $this->response->setJSON($result ?: [
            'success' => true,
            'message' => 'Request created successfully',
        ]);

    } catch (\Exception $e) {

        //log_message('error', 'API Error: ' . $e->getMessage());

        return $this->response->setStatusCode(500)
            ->setJSON([
                'success' => false,
                'error'   => $e->getMessage(),
            ]);
    }
}


    public function update($id)
    {
        helper('api');

        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $response = $client->put(getAssetRequestApiUrl('/update/' . $id), [
                'headers' => $headers,
                'form_params' => [
                    'employee_id'        => "cXQxV0RpNllHRWZJTENKTlNzU3g1QT09",
                    'asset_id'           => $this->request->getPost('asset_id'),
                    'requested_quantity' => $this->request->getPost('requested_quantity'),
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'Request updated successfully',
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
        }
    }

    public function updateStatus()
    {
        helper('api');

        $client  = getApiClient();
        $headers = getApiHeaders();

        $id     = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $remark = $this->request->getPost('remark');  
        if (!$id || !$status) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid data',
            ]);
        }

        try {
            $response = $client->post(getAssetRequestApiUrl('/act/' . $id), [
                'headers' => $headers,
                'form_params' => [
                    'status' => $status,
                    'remark'=>$remark
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'Request status updated'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        helper('api');

        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $client->delete(getAssetRequestApiUrl('/delete/' . $id), [
                'headers' => $headers,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Request deleted successfully',
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
        }
    }
    private function getAssetList(): array
    {
        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $response = $client->get(getAssetApiUrl('/list'), [
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
        helper('api');

        $encryptedId = $this->request->getPost('id');

        if (!$encryptedId) {
            return $this->response->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'error' => 'Request ID is required',
                ]);
        }

        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $response = $client->get(getAssetRequestApiUrl('/get/' . $encryptedId), [
                'headers' => $headers,
            ]);

            $result  = json_decode($response->getBody(), true);
            $request = $result['data'] ?? null;

            if (!$request) {
                return $this->response->setStatusCode(404)
                    ->setJSON([
                        'success' => false,
                        'error' => 'Request not found',
                    ]);
            }
            $assets    = $this->getAssetList();

            return view('frontend/assetRequest/edit-form', array_merge(
                compact('request', 'assets'),
                ['organizations' => $this->organizations]
            ));

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'error' => 'Failed to fetch request: ' . $e->getMessage(),
                ]);
        }
    }
}
