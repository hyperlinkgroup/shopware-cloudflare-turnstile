<?php

declare(strict_types=1);

namespace Ssq\CloudflareTurnstile\Storefront\Framework\Captcha;

use Ssq\CloudflareTurnstile\Api\ClientInterface;
use Shopware\Storefront\Framework\Captcha\AbstractCaptcha;
use Symfony\Component\HttpFoundation\Request;

class CloudflareTurnstile extends AbstractCaptcha
{
    public const CAPTCHA_NAME = 'cloudflareTurnstile';
    public const CAPTCHA_REQUEST_PARAMETER = 'cf-turnstile-response';
    private const CONFIG = 'config';

    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, array $captchaConfig): bool
    {
        $parent = parent::supports($request, $captchaConfig);

        if (!$parent) {
            return false;
        }

        return $this->isValidConfig($captchaConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Request $request, array $captchaConfig): bool
    {
        /** @var string|null $turnstileResponse */
        $turnstileResponse = $request->request->get(self::CAPTCHA_REQUEST_PARAMETER);

        if (null === $turnstileResponse || '' === $turnstileResponse) {
            return false;
        }

        return $this->client->isValidSolution(
            $turnstileResponse,
            $captchaConfig[self::CONFIG]['secretKey'],
            $request->getClientIp()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::CAPTCHA_NAME;
    }

    private function isValidConfig(array $captchaConfig): bool
    {
        $secretKey = $captchaConfig[self::CONFIG]['secretKey'] ?? null;

        if (null === $secretKey || '' === $secretKey || !\is_string($secretKey)) {
            return false;
        }

        $siteKey = $captchaConfig[self::CONFIG]['siteKey'] ?? null;

        return null !== $siteKey && '' !== $siteKey && \is_string($siteKey);
    }
}

