<?php

declare(strict_types = 1);

namespace App\Services;

use App\Enums\UserProfileType;
use App\Exceptions\BinotelServiceException;
use App\Models\UserProfile;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BinotelService
{
    private string $apiHost;

    private string $apiKey;

    private string $apiSecret;

    private string $apiFormat = 'json';

    public function __construct(
        private Client $client,
    ) {
        $this->apiHost   = config('binotel.apiHost');
        $this->apiKey    = config('binotel.apiKey');
        $this->apiSecret = config('binotel.apiSecret');
    }

    public function createCustomer(UserProfile $profile)
    {
        try {
            $responseData = $this->httpRequestData('customers/create', $this->params($profile));
        } catch (BinotelServiceException $e) {
            report($e);
        }

        $customerID = $responseData?->customerID;

        if ($customerID) {
            $profile->binotel_id = $customerID;
            $profile->save();
        }
    }

    public function updateCustomer(UserProfile $profile)
    {
        $customerID = $profile->binotel_id;

        if (!$customerID) {
            $this->createCustomer($profile);

            return;
        }

        try {
            $this->httpRequestData(
                'customers/update',
                array_merge($this->params($profile), ['id' => $customerID])
            );
        } catch (BinotelServiceException $e) {
            report($e);
        }
    }

    private function httpRequestData(string $uri, array $params = []): ?object
    {
        if ('' === $this->apiHost) {
            return null;
        }

        $url = "{$this->apiHost}/{$uri}.{$this->apiFormat}";

        try {
            $response = $this->client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => array_merge($params, [
                    'key'    => $this->apiKey,
                    'secret' => $this->apiSecret,
                ]),
                'http_errors' => false,
            ]);
        } catch (GuzzleException $e) {
            throw new BinotelServiceException(json_encode(['params' => $params, 'guzzle_exception' => $e]));
        }

        $content = $response->getBody()->getContents();
        $body    = json_decode($content);

        if ('error' === $body->status) {
            throw new BinotelServiceException(json_encode(['params' => $params, 'response' => $body]));
        }

        if ('success' === $body->status) {
            return $body;
        }

        throw new BinotelServiceException('Unknown error');
    }

    private function params(UserProfile $profile): array
    {
        return [
            'name' => $profile->typeAsEnum()->equals(UserProfileType::LEGAL_PERSON())
                ? $profile->organization_name . '/' . $profile->fullName
                : $profile->fullName,
            'numbers' => !$profile->typeAsEnum()->equals(UserProfileType::PHYSICAL_PERSON())
                ? [$profile->phone, $profile->contact_phone]
                : [$profile->phone],
            'email'       => $profile->user->email,
            'description' => $profile->typeAsEnum()->equals(UserProfileType::LEGAL_PERSON())
                ? trans('binotel.legal_person_description', [
                    'warehouse_manager_name'  => $profile->warehouse_manager_name,
                    'warehouse_manager_phone' => $profile->warehouse_manager_phone,
                    'physical_address'        => $profile->physical_address,
                ])
                : '',
        ];
    }
}
