<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\NewPostServiceException;
use GuzzleHttp\Client;

class NewPostService
{
    private const SEARCH_CITIES_LIMIT = 20;

    private string $baseUrl;

    private string $apiKey;

    private array $citySelectArray;

    public function __construct(private Client $client)
    {
        $this->baseUrl = config('delivery_services.new_post.baseUrl');
        $this->apiKey  = config('delivery_services.new_post.apiKey');
    }

    public function cities(?string $cityRef = null): array
    {
        $requestBody = [
            'modelName'    => 'Address',
            'calledMethod' => 'getCities',
        ];

        if ($cityRef) {
            $requestBody['methodProperties'] = [
                'Ref' => $cityRef,
            ];
        }

        return $this->httpRequestData($requestBody);
    }

    public function searchCities(string $cityName): array
    {
        $requestBody = [
            'modelName'        => 'Address',
            'calledMethod'     => 'searchSettlements',
            'methodProperties' => [
                'CityName' => $cityName,
                'Limit'    => self::SEARCH_CITIES_LIMIT,
            ],
        ];

        return $this->httpRequestData($requestBody)[0]?->Addresses ?? [];
    }

    public function warehouses(?string $cityRef = null): array
    {
        $methodProperties = [];

        if ($cityRef) {
            $methodProperties['CityRef'] = $cityRef;
        }

        $requestBody = [
            'modelName'        => 'AddressGeneral',
            'calledMethod'     => 'getWarehouses',
            'methodProperties' => $methodProperties,
        ];

        return $this->httpRequestData($requestBody);
    }

    public function searchCitiesSelectArray(string $cityName): array
    {
        $cities = $this->searchCities($cityName);

        $selectArray = [];

        foreach ($cities as $city) {
            $selectArray[$city->DeliveryCity] = $city->Present;
        }

        return $selectArray;
    }

    public function citySelectArray(?string $cityRef = null): array
    {
        if (isset($this->citySelectArray, $this->citySelectArray[$cityRef])) {
            return $this->citySelectArray[$cityRef];
        }

        $cities = $this->cities($cityRef);

        $selectArray = [];

        foreach ($cities as $city) {
            $selectArray[$city->Ref] = $city->Description;
        }

        if (isset($this->citySelectArray)) {
            $this->citySelectArray[$cityRef] = $selectArray;
        } else {
            $this->citySelectArray = [$cityRef => $selectArray];
        }

        return $selectArray;
    }

    public function warehouseSelectArray(?string $cityRef = null): array
    {
        $warehouses = $this->warehouses($cityRef);

        $selectArray = [];

        foreach ($warehouses as $warehouse) {
            $selectArray[$warehouse->Ref] = $warehouse->Description;
        }

        return $selectArray;
    }

    private function httpRequestData(array $body = []): array
    {
        $response = $this->client->request('POST', $this->baseUrl, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => array_merge($body, [
                'apiKey' => $this->apiKey,
            ]),
            'http_errors' => false,
        ]);

        $content = $response->getBody()->getContents();
        $body    = json_decode($content);

        if (true !== $body->success) {
            report(new NewPostServiceException($content));

            return [];
        }

        return $body->data;
    }
}
