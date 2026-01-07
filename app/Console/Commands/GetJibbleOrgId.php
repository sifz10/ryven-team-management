<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetJibbleOrgId extends Command
{
    protected $signature = 'jibble:get-org-id';
    protected $description = 'Fetch your Jibble Organization ID';

    public function handle()
    {
        $token = config('jibble.access_token');

        if (!$token) {
            $this->error('❌ JIBBLE_ACCESS_TOKEN not configured in .env');
            $this->line('Add: JIBBLE_ACCESS_TOKEN=your_token');
            return 1;
        }

        $this->info('🔍 Fetching Jibble organization information...');
        $this->line('Token: ' . substr($token, 0, 10) . '...' . substr($token, -10));
        $this->newLine();

        try {
            // The Jibble API requires organization ID in the path
            // Based on the Jibble docs, we need to get it from their dashboard
            // Let's try some common endpoints
            
            $endpoints = [
                'https://api.jibble.io/v1/organizations',
                'https://api.jibble.io/v1/me',
                'https://api.jibble.io/v1/profile',
            ];

            foreach ($endpoints as $endpoint) {
                $this->info("Trying: $endpoint");
                
                $response = Http::withToken($token)
                    ->timeout(10)
                    ->get($endpoint);

                $this->line('Status: ' . $response->status());
                
                if ($response->successful()) {
                    $data = $response->json();
                    $this->info('✅ Success!');
                    $this->newLine();
                    $this->line(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    $this->newLine();
                    
                    // Try to extract organization ID from various possible locations
                    $orgId = null;
                    
                    if (isset($data['data']['organizationId'])) {
                        $orgId = $data['data']['organizationId'];
                    } elseif (isset($data['data'][0]['id'])) {
                        $orgId = $data['data'][0]['id'];
                    } elseif (isset($data['data']['id'])) {
                        $orgId = $data['data']['id'];
                    } elseif (isset($data['organizationId'])) {
                        $orgId = $data['organizationId'];
                    }
                    
                    if ($orgId) {
                        $this->newLine();
                        $this->info("📋 Your Organization ID is: $orgId");
                        $this->line("\nAdd to your .env:");
                        $this->warn("JIBBLE_ORGANIZATION_ID=$orgId");
                        return 0;
                    } else {
                        $this->warn('Could not find organizationId in response. Check the JSON above.');
                    }
                } else {
                    $this->line('Response: ' . $response->status() . ' - ' . substr($response->body(), 0, 100));
                    $this->newLine();
                }
            }
            
            $this->error('❌ Could not fetch organization ID from Jibble API');
            $this->line('\n📖 Alternative: Get it manually from Jibble Dashboard:');
            $this->line('1. Go to https://www.jibble.io/');
            $this->line('2. Click Settings (gear icon)');
            $this->line('3. Select Organization Settings');
            $this->line('4. Your Organization ID is displayed there');
            
            return 1;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
