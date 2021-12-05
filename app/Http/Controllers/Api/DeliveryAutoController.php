<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Delivery\DeliveryCitiesRequest;
use App\Http\Requests\Delivery\DeliveryWarehousesRequest;
use App\Services\DeliveryAutoService;
use Illuminate\Http\JsonResponse;

class DeliveryAutoController
{
    public function __construct(
        private DeliveryAutoService $deliveryAutoService,
    ) {
    }

    public function warehouses(DeliveryWarehousesRequest $request): JsonResponse
    {
        $warehouses = $this->deliveryAutoService->warehouses($request->validated()['city_id']);

        $data = array_map(fn ($warehouse) => (object) [
            'id'   => $warehouse->id,
            'text' => $warehouse->address,
        ], $warehouses);

        return response()->json($data, 200);
    }

    public function cities(DeliveryCitiesRequest $request): JsonResponse
    {
        $cities = $this->deliveryAutoService->cities();

        $data = [];

        foreach ($cities as $city) {
            if (!str_starts_with(mb_strtolower($city->name), mb_strtolower($request->validated()['city_name']))) {
                continue;
            }

            $data[] = (object) [
                'id'   => $city->id,
                'text' => $city->name,
            ];
        }

        return response()->json($data, 200);
    }
}
