<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExternalDataService;
use Illuminate\Http\JsonResponse;

class RestController extends Controller
{
    protected ExternalDataService $externalDataService;

    public function __construct(ExternalDataService $externalDataService)
    {
        $this->externalDataService = $externalDataService;
    }

    /**
     * Verileri dış servisten çekip döndürür.
     */
    public function fetch(): JsonResponse
    {
        try {
            $data = $this->externalDataService->fetchData();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
