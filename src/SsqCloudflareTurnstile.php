<?php

declare(strict_types=1);

namespace Ssq\CloudflareTurnstile;

use Doctrine\DBAL\Connection;
use Ssq\CloudflareTurnstile\Config\Manager;
use Ssq\CloudflareTurnstile\Config\ManagerInterface;
use Ssq\CloudflareTurnstile\Exception\MissingActiveCaptchasConfigException;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class SsqCloudflareTurnstile extends Plugin
{
    public const CONFIG_KEY = 'core.basicInformation.activeCaptchasV2.cloudflareTurnstile';

    /**
     * @throws MissingActiveCaptchasConfigException
     * @throws \JsonException
     */
    public function deactivate(DeactivateContext $deactivateContext): void
    {
        // disable captcha on deactivation because shopware would
        // try to load the captcha template and break otherwise
        $this->getManager()->updateConfig($this->getConnection(), [
            'isActive' => false,
        ]);
    }

    /**
     * @throws MissingActiveCaptchasConfigException
     * @throws \JsonException
     */
    public function uninstall(UninstallContext $uninstallContext): void
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }

        // cleanup data – remove secret keys from the database
        Manager::create()->updateConfig($this->getConnection(), []);
    }

    protected function getConnection(): Connection
    {
        $connection = $this->container->get(Connection::class);

        if (!$connection instanceof Connection) {
            throw new \RuntimeException('Could not retrieve database connection.');
        }

        return $connection;
    }

    protected function getManager(): ManagerInterface
    {
        $manager = $this->container->get(ManagerInterface::class);

        if (!$manager instanceof ManagerInterface) {
            throw new \RuntimeException('Could not retrieve config manager.');
        }

        return $manager;
    }
}

