<?php

declare(strict_types=1);

namespace Ssq\CloudflareTurnstile\Exception;

class MissingActiveCaptchasConfigException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('The system config entry "core.basicInformation.activeCaptchasV2" is missing.');
    }
}

