<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FcmService
{
    protected $client;
    protected $projectId;
    protected $serviceAccountPath;
    protected $serviceAccountJson;
    protected $accessToken;

    public function __construct(Client $client = null)
    {
        // allow injecting a custom HTTP client for testing/flexibility
        $this->client = $client ?: new Client();
        $this->projectId = config('services.fcm.project_id');
        $this->serviceAccountPath = config('services.fcm.service_account_path')
            ?: env('GOOGLE_APPLICATION_CREDENTIALS')
            ?: storage_path('app/firebase-service-account.json');
        $this->serviceAccountJson = config('services.fcm.service_account_json');
    }

    /**
     * Get OAuth2 access token using service account
     */
    protected function getAccessToken()
    {
        // Cache the token for 55 minutes (tokens are valid for 1 hour)
        return Cache::remember('fcm_access_token', 55 * 60, function () {
            try {
                $serviceAccount = $this->loadServiceAccount();

                // Create JWT
                $now = time();
                $header = [
                    'alg' => 'RS256',
                    'typ' => 'JWT',
                ];

                $payload = [
                    'iss' => $serviceAccount['client_email'],
                    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                    'aud' => 'https://oauth2.googleapis.com/token',
                    'iat' => $now,
                    'exp' => $now + 3600,
                ];

                $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
                $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));
                
                $signatureInput = $base64UrlHeader . '.' . $base64UrlPayload;
                
                // Sign with private key
                $privateKey = openssl_pkey_get_private($serviceAccount['private_key']);
                openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
                openssl_free_key($privateKey);
                
                $base64UrlSignature = $this->base64UrlEncode($signature);
                $jwt = $signatureInput . '.' . $base64UrlSignature;

                // Exchange JWT for access token
                $response = $this->client->post('https://oauth2.googleapis.com/token', [
                    'form_params' => [
                        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                        'assertion' => $jwt,
                    ],
                ]);

                $result = json_decode($response->getBody()->getContents(), true);
                
                if (!isset($result['access_token'])) {
                    throw new \Exception('Failed to get access token from response');
                }

                Log::info('FCM access token obtained successfully');
                
                return $result['access_token'];
            } catch (\Exception $e) {
                Log::error('Failed to get FCM access token', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
                throw $e;
            }
        });
    }

    /**
     * Load Firebase service account from env JSON or file path.
     */
    protected function loadServiceAccount(): array
    {
        if (!empty($this->serviceAccountJson)) {
            $raw = trim($this->serviceAccountJson);
            $serviceAccount = json_decode($raw, true);

            // allow base64-encoded JSON for safer env storage
            if (!$serviceAccount) {
                $decoded = base64_decode($raw, true);
                if ($decoded !== false) {
                    $serviceAccount = json_decode($decoded, true);
                }
            }

            if (is_array($serviceAccount) && isset($serviceAccount['private_key'], $serviceAccount['client_email'])) {
                return $serviceAccount;
            }

            throw new \Exception('Invalid FIREBASE_SERVICE_ACCOUNT_JSON (expected raw or base64 JSON service account)');
        }

        if (!empty($this->serviceAccountPath) && file_exists($this->serviceAccountPath)) {
            $serviceAccount = json_decode(file_get_contents($this->serviceAccountPath), true);
            if (is_array($serviceAccount) && isset($serviceAccount['private_key'], $serviceAccount['client_email'])) {
                return $serviceAccount;
            }
            throw new \Exception('Invalid service account JSON at: ' . $this->serviceAccountPath);
        }

        throw new \Exception(
            'Firebase service account not found. Set FIREBASE_SERVICE_ACCOUNT_PATH or FIREBASE_SERVICE_ACCOUNT_JSON.'
        );
    }

    /**
     * Base64 URL encode
     */
    protected function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Send notification to a single device using FCM V1 API
     */
    public function sendToDevice(string $deviceToken, array $notification, array $data = [])
    {
        try {
            $accessToken = $this->getAccessToken();
            
            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
            
            $message = [
                'message' => [
                    'token' => $deviceToken,
                    'notification' => [
                        'title' => $notification['title'] ?? '',
                        'body' => $notification['body'] ?? '',
                    ],
                    'data' => array_map('strval', $data), // Convert all data values to strings
                    'webpush' => [
                        'notification' => [
                            'title' => $notification['title'] ?? '',
                            'body' => $notification['body'] ?? '',
                            'icon' => url('/favicon.svg'),
                            'badge' => url('/favicon.svg'),
                            'requireInteraction' => true,
                            'tag' => $data['type'] ?? 'order-notification',
                        ],
                        'fcm_options' => [
                            'link' => $data['link'] ?? url('/'),
                        ],
                        'headers' => [
                            'Urgency' => 'high',
                        ],
                    ],
                ],
            ];

            Log::info('Sending FCM V1 notification', [
                'url' => $url,
                'token' => substr($deviceToken, 0, 20) . '...',
                'notification' => $notification,
            ]);

            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $message,
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            Log::info('FCM V1 notification sent successfully', [
                'result' => $result,
            ]);

            return [
                'success' => 1,
                'failure' => 0,
                'results' => [$result],
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            
            Log::error('FCM V1 notification failed - Client Error', [
                'status' => $e->getResponse()->getStatusCode(),
                'error' => $e->getMessage(),
                'response' => $responseBody,
                'token' => substr($deviceToken, 0, 20) . '...',
            ]);

            // attempt to clean up invalid tokens automatically
            $this->cleanupInvalidToken($deviceToken, $responseBody);

            return [
                'success' => 0,
                'failure' => 1,
                'error' => $e->getMessage(),
                'response' => $responseBody,
            ];
        } catch (\Exception $e) {
            Log::error('FCM V1 notification failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => 0,
                'failure' => 1,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Inspect an error response body for invalid tokens and clear them.
     */
    protected function cleanupInvalidToken(string $deviceToken, string $responseBody)
    {
        $invalidIndicators = [
            'NotRegistered',
            'InvalidRegistration',
            'MissingRegistration',
            'registration_token_not_registered',
            'UNREGISTERED',
            'NOT_FOUND',
            'Requested entity was not found',
        ];

        foreach ($invalidIndicators as $indicator) {
            if (str_contains($responseBody, $indicator)) {
                try {
                    \App\Models\User::where('fcm_token', $deviceToken)
                        ->update(['fcm_token' => null]);
                    Log::info('Cleared invalid FCM token', ['token' => substr($deviceToken, 0, 20) . '...']);
                } catch (\Exception $ex) {
                    Log::error('Error clearing invalid FCM token', ['error' => $ex->getMessage()]);
                }
                break;
            }
        }
    }

    /**
     * Send notification to multiple devices
     */
    public function sendToDevices(array $deviceTokens, array $notification, array $data = [])
    {
        $results = [
            'success' => 0,
            'failure' => 0,
            'results' => [],
        ];

        foreach ($deviceTokens as $token) {
            $result = $this->sendToDevice($token, $notification, $data);
            $results['success'] += $result['success'];
            $results['failure'] += $result['failure'];
            $results['results'][] = $result;
        }

        return $results;
    }

    /**
     * Send notification to a topic
     */
    public function sendToTopic(string $topic, array $notification, array $data = [])
    {
        try {
            $accessToken = $this->getAccessToken();
            
            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
            
            $message = [
                'message' => [
                    'topic' => $topic,
                    'notification' => [
                        'title' => $notification['title'] ?? '',
                        'body' => $notification['body'] ?? '',
                    ],
                    'data' => $data,
                ],
            ];

            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $message,
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            Log::info('FCM V1 topic notification sent successfully', [
                'topic' => $topic,
                'result' => $result,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('FCM V1 topic notification failed', [
                'error' => $e->getMessage(),
                'topic' => $topic,
            ]);

            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Subscribe device to topic (V1 API uses different endpoint)
     */
    public function subscribeToTopic(array $deviceTokens, string $topic)
    {
        try {
            $accessToken = $this->getAccessToken();
            
            $response = $this->client->post("https://iid.googleapis.com/iid/v1:batchAdd", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => '/topics/' . $topic,
                    'registration_tokens' => $deviceTokens,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('FCM topic subscription failed', [
                'error' => $e->getMessage(),
                'topic' => $topic,
            ]);

            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Unsubscribe device from topic
     */
    public function unsubscribeFromTopic(array $deviceTokens, string $topic)
    {
        try {
            $accessToken = $this->getAccessToken();
            
            $response = $this->client->post("https://iid.googleapis.com/iid/v1:batchRemove", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => '/topics/' . $topic,
                    'registration_tokens' => $deviceTokens,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('FCM topic unsubscription failed', [
                'error' => $e->getMessage(),
                'topic' => $topic,
            ]);

            return ['error' => $e->getMessage()];
        }
    }
}
