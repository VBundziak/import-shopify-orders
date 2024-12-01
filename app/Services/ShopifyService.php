<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class ShopifyService
{
    private string $apiKey;
    private string $password;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('SHOPIFY_API_KEY');
        $this->password = env('SHOPIFY_API_PASSWORD');
        $this->baseUrl = env('SHOPIFY_BASE_URL');
    }

    /**
     * Fetch orders from Shopify API.
     *
     * @throws Exception
     */
    public function getOrders(): array
    {
        // API URL
        $url = "{$this->baseUrl}/admin/orders.json";

        // Initialize cURL request
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => "{$this->apiKey}:{$this->password}",
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // Log the API response
//        $this->logApiResponse($url, $httpCode, $response);

        // Handle errors
        if ($httpCode !== 200) {
            throw new Exception('Failed to fetch data from Shopify. HTTP Code: ' . $httpCode);
        }

        return json_decode($response, true);
    }

    /**
     * Log the API response to a file.
     *
     * @param string $url
     * @param int $httpCode
     * @param string $response
     */
    private function logApiResponse(string $url, int $httpCode, string $response): void
    {
        $logData = [
            'url' => $url,
            'http_code' => $httpCode,
            'response' => $response,
        ];

        Log::channel('shopify')->info('Shopify API Response', $logData);
    }
}
