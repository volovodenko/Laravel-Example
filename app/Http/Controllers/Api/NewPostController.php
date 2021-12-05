<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Delivery\DeliveryCitiesRequest;
use App\Http\Requests\Delivery\DeliveryWarehousesRequest;
use App\Services\NewPostService;

class NewPostController
{
    public function __construct(private NewPostService $newPostService)
    {
    }

    public function warehouses(DeliveryWarehousesRequest $request)
    {
        $warehouses = $this->newPostService->warehouses($request->validated()['city_id']);

        $data = array_map(fn ($warehouse) => (object) [
            'id'   => $warehouse->Ref,
            'text' => $warehouse->Description,
        ], $warehouses);

        return response()->json($data, 200);
    }

    public function cities(DeliveryCitiesRequest $request)
    {
        $cities = $this->newPostService->searchCities($request->validated()['city_name']);

        $data = array_map(fn ($city) => (object) [
            'id'   => $city->DeliveryCity,
            'text' => $city->Present,
        ], $cities);

        return response()->json($data, 200);
    }
}
