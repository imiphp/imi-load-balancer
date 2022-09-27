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

    private array $config = [];

    /**
     * @var IService[]
     */
    private array $instances = [];

    private int $lastGetTime = 0;

    private bool $isGetting = false;

    public function __construct(string $serviceId, IDiscoveryDriver $driver, array $config = [])
    {
        $this->serviceId = $serviceId;
        $this->driver = $driver;
        $this->config = $config;
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
        $cacheTTL = $this->config['cacheTTL'] ?? 60;
        if ($cacheTTL <= 0)
        {
            return $this->driver->getInstances($this->serviceId);
        }
        if (!$this->isGetting && time() - $this->lastGetTime > $cacheTTL)
        {
            $this->isGetting = true;
            try
            {
                $this->instances = $this->driver->getInstances($this->serviceId);
                $this->lastGetTime = time();
            }
            finally
            {
                $this->isGetting = false;
            }
        }

        return $this->instances;
    }
}
