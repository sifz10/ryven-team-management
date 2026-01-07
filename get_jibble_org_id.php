<?php

use Illuminate\Support\Facades\Http;

// Get the token from .env
$token = env('JIBBLE_ACCESS_TOKEN');

if (!$token) {
    echo "❌ JIBBLE_ACCESS_TOKEN not found in .env\n";
    exit(1);
}

echo "🔍 Fetching Jibble organization information...\n";
echo "Token: " . substr($token, 0, 10) . "..." . substr($token, -10) . "\n\n";

// Try multiple endpoints to get organization info
$endpoints = [
    'https://api.jibble.io/v1/user',
    'https://api.jibble.io/v1/organizations',
];

foreach ($endpoints as $endpoint) {
    echo "Trying: $endpoint\n";
    
    try {
        $response = Http::withToken($token)
            ->timeout(10)
            ->get($endpoint);

        echo "Status: " . $response->status() . "\n";
        
        if ($response->successful()) {
            $data = $response->json();
            echo "✅ Success!\n";
            echo json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
            
            // Try to extract organization ID
            if (isset($data['data']['organizationId'])) {
                echo "📋 Organization ID: " . $data['data']['organizationId'] . "\n";
            } elseif (isset($data['data'][0]['id'])) {
                echo "📋 Organization ID: " . $data['data'][0]['id'] . "\n";
            }
        } else {
            echo "❌ Error: " . $response->body() . "\n\n";
        }
    } catch (\Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n\n";
    }
}
