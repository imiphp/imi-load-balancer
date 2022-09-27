<?php

declare(strict_types=1);

namespace Imi\Service\Discovery;

use Imi\Service\Discovery\Contract\IDiscoveryClient;
use Imi\Service\Discovery\Contract\IDiscoveryDriver;

/**
 * 服务发现客户端.
 */
class DiscoveryClient implements IDiscoveryClient
{
    private string $serviceId = '';

    private IDiscoveryDriver $driver;

    public function __construct(string $serviceId, IDiscoveryDriver $driver)
    {
        $this->serviceId = $serviceId;
        $this->driver = $driver;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    /**
     * {@inheritDoc}
     */
    public function getInstances(): array
    {
        return $this->driver->getInstances($this->serviceId);
    }
}
