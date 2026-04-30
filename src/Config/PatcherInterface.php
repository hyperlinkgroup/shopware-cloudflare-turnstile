<?php

declare(strict_types=1);

namespace Ssq\CloudflareTurnstile\Config;

interface PatcherInterface
{
    /**
     * @param array<string, mixed> $configPresent
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     *
     * @throws \JsonException
     */
    public function patchValue(array $configPresent, array $data): array;
}

