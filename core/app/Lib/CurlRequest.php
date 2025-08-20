<?php

namespace App\Lib;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurlRequest
{
    /**
     * GET request using Laravel HTTP Client
     *
     * @param string $url
     * @param array|null $header
     * @return mixed
     */
    public static function curlContent($url, $header = null)
    {
        try {
            $request = Http::withoutVerifying() // Equivalent to SSL_VERIFYHOST and SSL_VERIFYPEER false
                ->timeout(30); // Default timeout

            // Add headers if provided
            if ($header) {
                $request = $request->withHeaders(self::formatHeaders($header));
            }

            $response = $request->get($url);

            // Return the response body as string (same as curl_exec)
            return $response->body();

        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('HTTP GET Request failed: ' . $e->getMessage(), [
                'url' => $url,
                'headers' => $header
            ]);
            
            return false; // Return false on error (similar to curl behavior)
        }
    }

    /**
     * POST request using Laravel HTTP Client
     *
     * @param string $url
     * @param mixed $postData
     * @param array|null $header
     * @return mixed
     */
    public static function curlPostContent($url, $postData = null, $header = null)
    {
        try {
            $request = Http::withoutVerifying() // Equivalent to SSL_VERIFYHOST and SSL_VERIFYPEER false
                ->timeout(30); // Default timeout

            // Add headers if provided
            if ($header) {
                $request = $request->withHeaders(self::formatHeaders($header));
            }

            // Handle different types of POST data
            if (is_array($postData)) {
                // For form data
                $response = $request->asForm()->post($url, $postData);
            } elseif (is_string($postData)) {
                // For raw string data (JSON, XML, etc.)
                $response = $request->withBody($postData, 'application/x-www-form-urlencoded')
                    ->post($url);
            } else {
                // No data
                $response = $request->post($url);
            }

            // Return the response body as string (same as curl_exec)
            return $response->body();

        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('HTTP POST Request failed: ' . $e->getMessage(), [
                'url' => $url,
                'postData' => $postData,
                'headers' => $header
            ]);
            
            return false; // Return false on error (similar to curl behavior)
        }
    }

    /**
     * Format headers array for Laravel HTTP Client
     *
     * @param array $headers
     * @return array
     */
    private static function formatHeaders($headers)
    {
        $formattedHeaders = [];
        
        foreach ($headers as $header) {
            // Split header by ':' to get key-value pair
            if (strpos($header, ':') !== false) {
                list($key, $value) = explode(':', $header, 2);
                $formattedHeaders[trim($key)] = trim($value);
            } else {
                // Handle headers without ':' (like 'Content-Type: application/json')
                $formattedHeaders[] = $header;
            }
        }
        
        return $formattedHeaders;
    }

    /**
     * Alternative method with more curl-like options
     * Use this if you need more control over the request
     *
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public static function curlWithOptions($url, $options = [])
    {
        try {
            $request = Http::withoutVerifying()->timeout(30);

            // Apply options
            if (isset($options['headers'])) {
                $request = $request->withHeaders(self::formatHeaders($options['headers']));
            }

            if (isset($options['timeout'])) {
                $request = $request->timeout($options['timeout']);
            }

            if (isset($options['method'])) {
                $method = strtolower($options['method']);
                $data = $options['data'] ?? [];

                switch ($method) {
                    case 'post':
                        $response = $request->post($url, $data);
                        break;
                    case 'put':
                        $response = $request->put($url, $data);
                        break;
                    case 'patch':
                        $response = $request->patch($url, $data);
                        break;
                    case 'delete':
                        $response = $request->delete($url, $data);
                        break;
                    default:
                        $response = $request->get($url, $data);
                }
            } else {
                $response = $request->get($url);
            }

            return $response->body();

        } catch (\Exception $e) {
            Log::error('HTTP Request with options failed: ' . $e->getMessage(), [
                'url' => $url,
                'options' => $options
            ]);
            
            return false;
        }
    }
}