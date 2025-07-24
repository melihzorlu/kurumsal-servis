<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SoapService;
use Illuminate\Http\JsonResponse;

class SoapDataController extends Controller
{
    public function index(SoapService $soapService): JsonResponse
    {
        try {
            $data = $soapService->callExample();
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'SOAP hatası', 'message' => $e->getMessage()], 500);
        }
    }
}
