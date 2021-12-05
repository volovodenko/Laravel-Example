<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\DeliveryAutoServiceException;
use GuzzleHttp\Client;

class DeliveryAutoService
{
    private string $baseUrl;

    private string $culture;

    private int $country;

    private array $citySelectArray;

    public function __construct(private Client $client)
    {
        $this->baseUrl = config('delivery_services.delivery_auto.baseUrl');
        $this->culture = config('delivery_services.delivery_auto.culture');
        $this->country = config('delivery_services.delivery_auto.country');
    }

    public function cities(): array
    {
        $uri = $this->baseUrl . '/Public/GetAreasList';

        return $this->httpRequestData($uri);
    }

    public function warehouses(?string $cityId = null): array
    {
        $query = [
            'includeRegionalCenters' => true,
            'CityId'                 => $cityId,
        ];

        return $this->httpRequestData($this->warehousesUrl(), $query);
    }

    public function citySelectArray(): array
    {
        if (isset($this->citySelectArray)) {
            return $this->citySelectArray;
        }

        $cities = $this->cities();

        $selectArray = [];

        foreach ($cities as $city) {
            $selectArray[$city->id] = $city->name;
        }

        $this->citySelectArray = $selectArray;

        return $this->citySelectArray;
    }

    public function warehouseSelectArray(?string $cityId = null): array
    {
        $warehouses = $this->warehouses($cityId);

        $selectArray = [];

        foreach ($warehouses as $warehouse) {
            $selectArray[$warehouse->id] = $warehouse->address;
        }

        return $selectArray;
    }

    private function warehousesUrl(): string
    {
        return $this->baseUrl . '/Public/GetWarehousesList';
    }

    private function httpRequestData(string $uri, array $query = []): array
    {
        $response = $this->client->request('GET', $uri, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'query' => array_merge($query, [
                'culture' => $this->culture,
                'country' => $this->country,
            ]),
            'http_errors' => false,
        ]);

        $content = $response->getBody()->getContents();
        $body    = json_decode($content);

        if (true !== $body->status) {
            report(new DeliveryAutoServiceException($content));

            return [];
        }

        return $body->data;
    }
}
