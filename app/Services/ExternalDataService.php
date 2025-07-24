<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\ExternalData;
use App\Models\IntegrationLog;


IntegrationLog::create([
    'service_name' => 'REST Placeholder',
    'status'       => 'success',
    'message'      => 'Veri başarıyla alındı ve kaydedildi.',
    'executed_at'  => now(),
]);


class ExternalDataService
{
    protected string $cacheKey = 'external_data';

    public function fetchData(): array
    {
        return Cache::remember($this->cacheKey, now()->addMinutes(30), function () {
            try {
                $response = Http::get('https://jsonplaceholder.typicode.com/posts');

                if ($response->successful()) {
                    $data = $response->json();

                    foreach ($data as $item) {
                        ExternalData::updateOrCreate(
                            ['external_id' => $item['id']],
                            [
                                'title' => $item['title'],
                                'body' => $item['body'],
                            ]
                        );
                    }

                    IntegrationLog::create([
                        'source' => 'jsonplaceholder',
                        'status' => 'success',
                        'message' => 'Veriler başarıyla alındı',
                    ]);

                    return $data;
                } else {
                    IntegrationLog::create([
                        'source' => 'jsonplaceholder',
                        'status' => 'error',
                        'message' => 'API başarısız döndü: ' . $response->status(),
                    ]);
                    return [];
                }
            } catch (\Throwable $e) {
                IntegrationLog::create([
                    'source' => 'jsonplaceholder',
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
                return [];
            }
        });
    }
}
