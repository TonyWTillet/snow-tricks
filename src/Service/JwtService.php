<?php

namespace App\Service;

class JwtService
{
    /**
     * Generate a JWT token
     *
     * @param array $header
     * @param array $payload
     * @param string $secret
     * @param int $validity
     * @return string
     */
    public function generateToken(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        if ($validity > 0) {
            $now = new \DateTimeImmutable();
            $expiration = $now->getTimestamp() + $validity;

            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $expiration;
        }



        $jwt = base64_encode(json_encode($header)) . '.' . base64_encode(json_encode($payload));
        $jwt = str_replace(['+', '/', '='], ['-', '_', ''], $jwt);

        $secret = base64_encode($secret);

        $signature = hash_hmac('sha256', $jwt, $secret, true);

        $signature = base64_encode($signature);
        $signature = str_replace(['+', '/', '='], ['-', '_', ''], $signature);

        return $jwt . '.' . $signature;
    }

    /**
     * Verify a JWT token
     *
     * @param string $token
     * @return bool
     */
    public function verifyToken(string $token): bool
    {
        return preg_match('/^[a-zA-Z0-9-\-\_\=]+\.[a-zA-Z0-9-\-\_\=]+\.[a-zA-Z0-9-\-\_\=]+$/', $token) === 1;
    }

    public function getPayload(string $token): array
    {
        $array = explode('.', $token);
        return json_decode(base64_decode($array[1]), true);
    }

    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);
        return $payload['exp'] < (new \DateTimeImmutable())->getTimestamp();
    }

    public function getHeader(string $token): array
    {
        $array = explode('.', $token);
        return json_decode(base64_decode($array[0]), true);
    }

    public function checkToken(string $token, string $secret): bool
    {
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        $verify = $this->generateToken($header, $payload, $secret, 0);

        return $verify === $token;
    }

}