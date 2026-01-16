<?php

namespace App\Controllers;

class EmployeeAssignedAssetController extends BaseController
{
    public function index()
    {
        // 1. Check employee session
        if (!session()->get('employee_token')) {
            return redirect()->to('/employee-login')
                ->with('error', 'Session expired. Please log in again.');
        }

        helper('api');

        // Get page from URL
        $page = (int) $this->request->getGet('page');
        $page = $page > 0 ? $page : 1;

        // Fixed page size
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $token   = session()->get('employee_token');
        $empName = session()->get('employee_name');

        $client  = getApiClient();
        $headers = getApiHeaders();
        $baseUrl = getAssetAssignmentApiUrl();

        // Build query string with filters AND pagination
        $queryParams = [
            'limit' => $limit,
            'offset' => $offset,
            'asset_name' => $this->request->getGet('asset'),
            'model' => $this->request->getGet('model'),
            'assigned_quantity' => $this->request->getGet('quantity'),
            'assigned_date' => $this->request->getGet('assigned_date'),
        ];

        // Remove empty parameters
        $queryParams = array_filter($queryParams, function($value) {
            return $value !== null && $value !== '';
        });

        $queryString = http_build_query($queryParams);

        $myAssignments = [];
        $totalPages = 1;

        try {
            $response = $client->get($baseUrl . '/list?' . $queryString, [
                'headers' => array_merge($headers, [
                    'Authorization' => 'Bearer ' . $token
                ])
            ]);

            $result = json_decode($response->getBody(), true);
            $allAssignments = $result['data'] ?? [];

            // Filter to get only this employee's assignments
            $myAssignments = array_filter($allAssignments, fn($a) =>
                strtolower(trim($a['employee_name'])) === strtolower(trim($empName))
            );

            // Get total count from all assignments (without pagination) for this employee
            $allQueryParams = array_filter([
                'asset_name' => $this->request->getGet('asset'),
                'model' => $this->request->getGet('model'),
                'assigned_quantity' => $this->request->getGet('quantity'),
                'assigned_date' => $this->request->getGet('assigned_date'),
            ], function($value) {
                return $value !== null && $value !== '';
            });

            $allQueryString = http_build_query($allQueryParams);

            $allResponse = $client->get($baseUrl . '/list?' . $allQueryString, [
                'headers' => array_merge($headers, [
                    'Authorization' => 'Bearer ' . $token
                ])
            ]);

            $allResult = json_decode($allResponse->getBody(), true);
            $allData = $allResult['data'] ?? [];
            
            $allMyAssignments = array_filter($allData, fn($a) =>
                strtolower(trim($a['employee_name'])) === strtolower(trim($empName))
            );

            $totalAssignments = count($allMyAssignments);

            // Pagination calculations
            $totalPages = $totalAssignments > 0 ? ceil($totalAssignments / $limit) : 1;
            $page = max(1, min($page, $totalPages));

        } catch (\Exception $e) {
            log_message('error', 'Assigned asset fetch error: ' . $e->getMessage());
        }

        return view('employees/assignedassets/assigned_assets_index', array_merge(
            compact('myAssignments', 'page', 'totalPages'),
            ['organizations' => $this->organizations]
        ));
    }
}
