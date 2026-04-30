<?php

declare(strict_types=1);

namespace Ssq\CloudflareTurnstile\Migration;

use Doctrine\DBAL\Connection;
use Ssq\CloudflareTurnstile\Config\Manager;
use Ssq\CloudflareTurnstile\Exception\MissingActiveCaptchasConfigException;
use Ssq\CloudflareTurnstile\Storefront\Framework\Captcha\CloudflareTurnstile;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1714400000SystemConfigCaptchaOptions extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1_714_400_000;
    }

    /**
     * @throws MissingActiveCaptchasConfigException
     * @throws \JsonException
     */
    public function update(Connection $connection): void
    {
        Manager::create()->updateConfig($connection, [
            'name' => CloudflareTurnstile::CAPTCHA_NAME,
            'isActive' => false,
            'config' => [
                'siteKey' => '',
                'secretKey' => '',

                // possible options: managed, non-interactive, invisible
                'widgetMode' => 'managed',

                // possible options: light, dark, auto
                'theme' => 'auto',
            ],
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // nothing to do here
    }
}

