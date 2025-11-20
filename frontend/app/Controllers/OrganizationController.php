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

        // Filters
        $name       = $this->request->getGet('name');
        $email      = $this->request->getGet('email');
        $contact_no = $this->request->getGet('contact_no');
        $address    = $this->request->getGet('address');
        $country    = $this->request->getGet('country');
        $state      = $this->request->getGet('state');
        $city       = $this->request->getGet('city');
        $zipcode    = $this->request->getGet('zipcode');

        try {
            $response = $client->get($apiBaseUrl . '/list', [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);
            $organizations = $result['data'] ?? [];

            // Apply filters locally
            if (!empty($name)) {
                $organizations = array_filter($organizations, fn($o) => stripos($o['name'], $name) !== false);
            }
            if (!empty($email)) {
                $organizations = array_filter($organizations, fn($o) => stripos($o['email'], $email) !== false);
            }
            if (!empty($contact_no)) {
                $organizations = array_filter($organizations, fn($o) => stripos($o['contact_no'], $contact_no) !== false);
            }
            if (!empty($address)) {
                $organizations = array_filter($organizations, fn($o) => stripos($o['address'], $address) !== false);
            }
            if (!empty($country)) {
                $organizations = array_filter($organizations, fn($o) => stripos($o['country'], $country) !== false);
            }
            if (!empty($state)) {
                $organizations = array_filter($organizations, fn($o) => stripos($o['state'], $state) !== false);
            }
            if (!empty($city)) {
                $organizations = array_filter($organizations, fn($o) => stripos($o['city'], $city) !== false);
            }
            if (!empty($zipcode)) {
                $organizations = array_filter($organizations, fn($o) => stripos($o['zipcode'], $zipcode) !== false);
            }

            return view('frontend/organization/organization-index', compact(
                'organizations',
                'name',
                'email',
                'contact_no',
                'address',
                'country',
                'state',
                'city',
                'zipcode'
            ));
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setBody('Error fetching organizations: ' . $e->getMessage());
        }
    }

    public function store()
    {
        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = 'http://localhost:3000/api/organization';

        try {
            $response = $client->post($apiBaseUrl . '/create', [
                'headers' => $headers,
                'form_params' => [
                    'name'       => $this->request->getPost('name'),
                    'email'      => $this->request->getPost('email'),
                    'contact_no' => $this->request->getPost('contact_no'),
                    'address'    => $this->request->getPost('address'),
                    'country'    => $this->request->getPost('country'),
                    'state'      => $this->request->getPost('state'),
                    'city'       => $this->request->getPost('city'),
                    'zipcode'    => $this->request->getPost('zipcode'),
                ]
            ]);

            return $this->response->setJSON(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to create organization: ' . $e->getMessage(),
            ]);
        }
    }
    

    public function update($id)
    {
        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = 'http://localhost:3000/api/organization';

        try {
            $response = $client->put($apiBaseUrl . '/update/' . $id, [
                'headers' => $headers,
                'form_params' => [
                    'name'       => $this->request->getPost('name'),
                    'email'      => $this->request->getPost('email'),
                    'contact_no' => $this->request->getPost('contact_no'),
                    'address'    => $this->request->getPost('address'),
                    'country'    => $this->request->getPost('country'),
                    'state'      => $this->request->getPost('state'),
                    'city'       => $this->request->getPost('city'),
                    'zipcode'    => $this->request->getPost('zipcode'),
                ]
            ]);

            return $this->response->setJSON(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to update organization: ' . $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = 'http://localhost:3000/api/organization';

        try {
            $client->delete($apiBaseUrl . '/delete/' . $id, [
                'headers' => $headers,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Organization deleted successfully',
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to delete organization: ' . $e->getMessage(),
            ]);
        }
    }

    public function editRecord()
    {
        $id = $this->request->getPost('id');

        $client = getApiClient();
        $headers = getApiHeaders();
        $apiBaseUrl = 'http://localhost:3000/api/organization';

        try {
            $response = $client->get($apiBaseUrl . '/get/' . $id, [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);
            $organization = $result['data'] ?? null;

            return view('frontend/organization/edit-form', [
                'organization' => $organization,
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to fetch organization data: ' . $e->getMessage(),
            ]);
        }
    }
}
