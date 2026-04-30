<?php

declare(strict_types=1);

namespace Ssq\CloudflareTurnstile\Config;

use Doctrine\DBAL\Connection;
use Ssq\CloudflareTurnstile\Exception\MissingActiveCaptchasConfigException;

interface ManagerInterface
{
    /**
     * @param array<string, mixed> $data
     *
     * @throws MissingActiveCaptchasConfigException
     * @throws \JsonException
     */
    public function updateConfig(Connection $connection, array $data): void;
}

