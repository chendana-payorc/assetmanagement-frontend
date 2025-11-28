<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Store organization data globally for all controllers/views.
     *
     * @var array
     */
    protected $organizations = [];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // 🔹 Use your API client
        $client  = getApiClient();
        $headers = getApiHeaders();

        $apiBaseUrl = 'http://localhost:3000/api/organization';

        try {
            $apiResponse = $client->get($apiBaseUrl . '/list', [
                'headers' => $headers,
            ]);

            $result = json_decode($apiResponse->getBody(), true);
            $this->organizations = $result['data'] ?? [];
        } catch (\Throwable $e) {
            // Handle API errors gracefully
            $this->organizations = [];
            log_message('error', 'Failed to fetch organizations: ' . $e->getMessage());
        }
    }
}