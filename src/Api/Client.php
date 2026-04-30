<?php

declare(strict_types=1);

namespace Ssq\CloudflareTurnstile\Api;

use GuzzleHttp\ClientInterface as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface
{
    public const TURNSTILE_VERIFY_ENDPOINT = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    private HttpClient $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidSolution(string $response, string $secretKey, ?string $remoteIp = null): bool
    {
        try {
            $result = $this->getValidationResponse($response, $secretKey, $remoteIp);

            return (bool) ($result['success'] ?? false);
        } catch (ClientExceptionInterface $exception) {
            // fail-open: if Cloudflare API is unreachable, let the request through
            // @see https://developers.cloudflare.com/turnstile/get-started/server-side-validation/
            return true;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws GuzzleException
     */
    public function getValidationResponse(string $response, string $secretKey, ?string $remoteIp = null): array
    {
        $formParams = [
            'secret' => $secretKey,
            'response' => $response,
        ];

        if (null !== $remoteIp) {
            $formParams['remoteip'] = $remoteIp;
        }

        $httpResponse = $this->client->request('POST', self::TURNSTILE_VERIFY_ENDPOINT, [
            'form_params' => $formParams,
        ]);

        return $this->decodeResponseBody($httpResponse);
    }

    /**
     * {@inheritdoc}
     */
    public function decodeResponseBody(ResponseInterface $response): array
    {
        $responseRaw = $response->getBody()->getContents();

        return json_decode($responseRaw, true, 512, \JSON_THROW_ON_ERROR);
    }
}

