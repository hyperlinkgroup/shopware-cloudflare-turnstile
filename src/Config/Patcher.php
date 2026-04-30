<?php

declare(strict_types=1);

namespace Ssq\CloudflareTurnstile\Config;

use Ssq\CloudflareTurnstile\Storefront\Framework\Captcha\CloudflareTurnstile;

class Patcher implements PatcherInterface
{
    /**
     * {@inheritdoc}
     */
    public function patchValue(array $configPresent, array $data): array
    {
        $configurationValue = json_decode($configPresent['configuration_value'], true, 512, \JSON_THROW_ON_ERROR);
        $value = $configurationValue['_value'];

        if ([] === $data) {
            // delete config
            unset($value[CloudflareTurnstile::CAPTCHA_NAME]);
        } elseif (!\array_key_exists(CloudflareTurnstile::CAPTCHA_NAME, $value)) {
            // create config
            $value[CloudflareTurnstile::CAPTCHA_NAME] = $data;
        } else {
            // merge config
            $value[CloudflareTurnstile::CAPTCHA_NAME] = $this->merge($value[CloudflareTurnstile::CAPTCHA_NAME], $data);
        }

        return $value;
    }

    /**
     * @param array<string, mixed> $value
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function merge(array $value, array $data): array
    {
        foreach ($data as $key => $item) {
            if (\is_array($item) && \array_key_exists($key, $value)) {
                $value[$key] = $this->merge($value[$key], $item);

                continue;
            }

            $value[$key] = $item;
        }

        return $value;
    }
}

