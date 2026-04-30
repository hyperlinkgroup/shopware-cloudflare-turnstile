<?php

declare(strict_types=1);

namespace Ssq\CloudflareTurnstile\Config;

use Doctrine\DBAL\Connection;
use Ssq\CloudflareTurnstile\Exception\MissingActiveCaptchasConfigException;

class Manager implements ManagerInterface
{
    private PatcherInterface $patcher;

    public function __construct(PatcherInterface $patcher)
    {
        $this->patcher = $patcher;
    }

    public static function create(): self
    {
        return new self(new Patcher());
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfig(Connection $connection, array $data): void
    {
        $configPresent = $connection->fetchAssociative(
            'SELECT * FROM `system_config` WHERE `configuration_key` = ?',
            ['core.basicInformation.activeCaptchasV2']
        );

        if (false === $configPresent) {
            throw new MissingActiveCaptchasConfigException();
        }

        $value = $this->patcher->patchValue($configPresent, $data);

        $connection->update('system_config', [
            'configuration_value' => json_encode([
                '_value' => $value,
            ], \JSON_THROW_ON_ERROR | \JSON_INVALID_UTF8_IGNORE),
        ], [
            'id' => $configPresent['id'],
        ]);
    }
}

