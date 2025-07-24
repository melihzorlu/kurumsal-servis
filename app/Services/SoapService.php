<?php

namespace App\Services;

use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Support\Facades\Cache;
use App\Models\IntegrationLog;
class SoapService
{
    protected SoapWrapper $soap;

    public function __construct(SoapWrapper $soap)
    {
        $this->soap = $soap;
    }
    public function fetchData(): array
    {
        try {
            $client = new \SoapClient('https://www.dataaccess.com/webservicesserver/NumberConversion.wso?WSDL');

            $response = $client->NumberToWords(['ubiNum' => 123]);


            IntegrationLog::create([
                'service_name' => 'SOAP NumberToWords',
                'status'       => 'success',
                'message'      => 'Veri başarıyla alındı: ' . json_encode($response),
                'executed_at'  => now(),
            ]);

            return [$response];
        } catch (\Exception $e) {

            IntegrationLog::create([
                'service_name' => 'SOAP NumberToWords',
                'status'       => 'fail',
                'message'      => $e->getMessage(),
                'executed_at'  => now(),
            ]);

            return [];
        }
    }

    public function callExample()
    {
        return Cache::remember('soap_example_data', 1800, function () {
            $this->soap->add('ExampleService', function ($service) {
                $service
                    ->wsdl('https://www.example.com/soap?wsdl') // <-- kendi WSDL’ini ekle
                    ->trace(true)
                    ->cache(WSDL_CACHE_NONE);
            });

            return $this->soap->call('ExampleService.MethodName', [
                'Param1' => 'value1',
                'Param2' => 'value2',
            ]);
        });
    }
}
