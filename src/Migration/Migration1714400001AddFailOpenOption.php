<?php

declare(strict_types=1);

namespace Ssq\CloudflareTurnstile\Migration;

use Doctrine\DBAL\Connection;
use Ssq\CloudflareTurnstile\Config\Manager;
use Ssq\CloudflareTurnstile\Exception\MissingActiveCaptchasConfigException;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1714400001AddFailOpenOption extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_714_400_001;
    }

    /**
     * @throws MissingActiveCaptchasConfigException
     * @throws \JsonException
     */
    public function update(Connection $connection): void
    {
        Manager::create()->updateConfig($connection, [
            'config' => [
                // fail-open: if true, requests are allowed through when the Cloudflare API is unreachable
                'failOpen' => true,
            ],
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // nothing to do here
    }
}

