<?php

declare(strict_types=1);

namespace Ssq\CloudflareTurnstile\Api;

use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    /**
     * Validates a Turnstile response token and returns whether the solution is valid.
     *
     * @throws \JsonException
     */
    public function isValidSolution(string $response, string $secretKey, ?string $remoteIp = null): bool;

    /**
     * Sends the verification request to Cloudflare and returns the decoded response.
     *
     * @return array<string, mixed>
     *
     * @throws \JsonException
     */
    public function getValidationResponse(string $response, string $secretKey, ?string $remoteIp = null): array;

    /**
     * Decodes a JSON response body.
     *
     * @return array<string, mixed>
     *
     * @throws \JsonException
     */
    public function decodeResponseBody(ResponseInterface $response): array;
}

