<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AssetHistoryController extends Controller
{
    public function index()
    {
        $token = session()->get('admin_token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        helper('api');

        // Get page from URL
        $page = (int) $this->request->getGet('page');
        $page = $page > 0 ? $page : 1;

        // Fixed page size
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $client      = getApiClient();
        $headers     = getApiHeaders();
        $apiBaseUrl  = getAssetHistoryApiUrl();

        // Build query string with filters AND pagination
        $queryParams = [
            'limit' => $limit,
            'offset' => $offset,
            'asset_name' => $this->request->getGet('asset_name'),
            'model' => $this->request->getGet('model'),
            'employee_name' => $this->request->getGet('employee_name'),
            'assigned_quantity' => $this->request->getGet('assigned_quantity'),
            'assigned_date' => $this->request->getGet('assigned_date'),
            'return_date' => $this->request->getGet('return_date'),
            'status' => $this->request->getGet('status'),
        ];

        // Remove empty parameters
        $queryParams = array_filter($queryParams, function($value) {
            return $value !== null && $value !== '';
        });

        $queryString = http_build_query($queryParams);

        try {
            // Get paginated data with filters
            $response = $client->get($apiBaseUrl . '/list?' . $queryString, [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);
            $history = $result['data'] ?? [];

            // Total history count from backend
            $totalHistory = $result['totalCount'] ?? 0;

            // Pagination calculations
            $totalPages = $totalHistory > 0 ? ceil($totalHistory / $limit) : 1;
            $page = max(1, min($page, $totalPages));

        } catch (\Exception $e) {
            $history = [];
            $totalPages = 1;
        }

        return view('frontend/assethistory/assethistory-index', array_merge(
            compact('history', 'page', 'totalPages'),
            ['organizations' => $this->organizations ?? []]
        ));
    }
}
