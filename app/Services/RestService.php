<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\ExternalPost;
use Exception;

class RestService
{
    protected string $endpoint = 'https://jsonplaceholder.typicode.com/posts';
    protected string $cacheKey = 'external_posts';
    protected int $cacheTTL = 1800; // 30 dakika

    public function fetchAndStore(): array
    {
        try {
            return Cache::remember($this->cacheKey, $this->cacheTTL, function () {
                $response = Http::get($this->endpoint);

                if (!$response->successful()) {
                    throw new Exception('API isteği başarısız.');
                }

                $posts = $response->json();

                foreach ($posts as $post) {
                    ExternalPost::updateOrCreate(
                        ['external_id' => $post['id']],
                        ['title' => $post['title'], 'body' => $post['body']]
                    );
                }

                return $posts;
            });
        } catch (\Throwable $e) {
            throw new Exception("Veri alınamadı: " . $e->getMessage());
        }
    }
}
