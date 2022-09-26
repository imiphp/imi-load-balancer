<?php

declare(strict_types=1);

namespace Imi\Service\Discovery;

use Imi\Service\Contract\IService;
use Imi\Service\Discovery\Contract\IDiscoveryClient;

/**
 * 服务发现客户端.
 */
class DiscoveryClient implements IDiscoveryClient
{
    /**
     * @var IService[]
     */
    private array $services;

    public function __construct(array $services)
    {
        $this->services = $services;
    }

    /**
     * @return IService[]
     */
    public function getServices(): array
    {
        return $this->services;
    }
}
