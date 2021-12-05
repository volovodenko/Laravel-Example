<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class JWT
{
    /**
     * Token expire in (hours).
     */
    private int $expIn;

    /**
     * Algorithm for hash.
     */
    private string $alg;

    /**
     * Key for signature.
     */
    private string $key;

    public function __construct()
    {
        $this->expIn = config('jwt.expires_in');
        $this->alg   = config('jwt.alg');
        $this->key   = config('app.key');
    }

    public function getToken(Model $entity, array $customPayload = []): string
    {
        $header = [
            'alg' => $this->alg,
            'typ' => 'JWT',
        ];

        $payload = array_merge([
            'id'  => $entity->id,
            'exp' => now()->addHours($this->expIn)->timestamp,
        ], $customPayload);

        $unsignedToken = base64_encode(json_encode($header)) . '.' . base64_encode(json_encode($payload));
        $signature     = base64_encode($this->getSignature($unsignedToken));

        return $unsignedToken . '.' . $signature;
    }

    public function isValidToken(?string $token, ?Model $entity): bool
    {
        if (!$token || !$entity) {
            return false;
        }

        $data = explode('.', $token);

        if (!isset($data[2])) {
            return false;
        }

        $header    = json_decode(base64_decode($data[0]), true);
        $payload   = json_decode(base64_decode($data[1]), true);
        $signature = base64_decode($data[2]);

        $checkNotPass = !isset($header['alg'])
            || $header['alg'] !== $this->alg
            || $signature !== $this->getSignature($data[0] . '.' . $data[1])
            || $entity->id != $payload['id']
            || now()->timestamp >= $payload['exp'];

        return !$checkNotPass;
    }

    protected function getSignature(string $data): string
    {
        return hash_hmac($this->alg, $data, $this->key);
    }
}
