<?php

use Config\Services;

if (!function_exists('getApiHeaders')) {
    function getApiHeaders()
    {
        helper('session');

        $token = session()->get('admin_token');

        $headers = [
            'Accept' => 'application/json',
            'username' => env('API_USERNAME'),
            'password' => env('API_PASSWORD'),
        ];

        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        return $headers;
    }
}

if (!function_exists('getApiClient')) {
    function getApiClient()
    {
        return Services::curlrequest();
    }
}

if (!function_exists('getApiBaseUrl')) {
    function getApiBaseUrl($endpoint = '')
    {
        return rtrim(env('API_BASE_URL'), '/') . '/admin' . $endpoint;
    }
}

if (!function_exists('getAssetApiUrl')) {
    function getAssetApiUrl($endpoint = '')
    {
        return rtrim(env('API_BASE_URL'), '/') . '/asset' . $endpoint;
    }
}

if (!function_exists('getDepartmentApiUrl')) {
    function getDepartmentApiUrl($endpoint = '')
    {
        return rtrim(env('API_BASE_URL'), '/') . '/department' . $endpoint;
    }
}

if (!function_exists('getDesignationApiUrl')) {
    function getDesignationApiUrl($endpoint = '')
    {
        return rtrim(env('API_BASE_URL'), '/') . '/designation' . $endpoint;
    }
    
}

if (!function_exists('getOrganizationApiUrl')) {
    function getOrganizationApiUrl($endpoint = '')
    {
        return rtrim(env('API_BASE_URL'), '/') . '/organization' . $endpoint;
    }
}


