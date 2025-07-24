<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\ExternalData;
use App\Models\IntegrationLog;

class ExternalDataController extends Controller
{
    public function index()
    {
        try {
            $cacheKey = 'external_data';
            $data = Cache::get($cacheKey);

            if (!$data) {
                $response = Http::get('https://jsonplaceholder.typicode.com/posts');

                if (!$response->successful()) {
                    IntegrationLog::create([
                        'source' => 'REST',
                        'request_url' => 'https://jsonplaceholder.typicode.com/posts',
                        'success' => false,
                        'error_message' => 'HTTP Status: ' . $response->status(),
                    ]);

                    Log::error('External API failed', ['status' => $response->status()]);
                    return response()->json(['error' => 'External API failed'], 500);
                }

                $responseData = $response->json();
                $normalizedData = [];

                foreach (array_slice($responseData, 0, 5) as $item) {
                    $record = ExternalData::updateOrCreate(
                        ['external_id' => $item['id']],
                        ['title' => $item['title'], 'content' => $item['body']]
                    );
                    $normalizedData[] = $record;
                }

                // Loglama
                IntegrationLog::create([
                    'source' => 'REST',
                    'request_url' => 'https://jsonplaceholder.typicode.com/posts',
                    'response' => json_encode($responseData),
                    'success' => true,
                ]);

                Cache::put($cacheKey, $normalizedData, now()->addMinutes(30));
                $data = $normalizedData;
            }

            return response()->json($data);
        } catch (\Throwable $e) {
            Log::error('ExternalData Error', ['message' => $e->getMessage()]);

            IntegrationLog::create([
                'source' => 'REST',
                'request_url' => 'https://jsonplaceholder.typicode.com/posts',
                'success' => false,
                'error_message' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
