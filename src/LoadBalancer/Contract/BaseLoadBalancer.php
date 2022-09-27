<?php

declare(strict_types=1);

namespace Imi\Service\LoadBalancer\Contract;

use Imi\Service\Contract\IService;
use Imi\Service\Discovery\Contract\IDiscoveryClient;

abstract class BaseLoadBalancer implements ILoadBalancer
{
    /**
     * @var IService[]
     */
    private array $services = [];

    private ?IDiscoveryClient $discoveryClient = null;

    /**
     * @param IService[]|IDiscoveryClient $services
     */
    public function __construct($services)
    {
        if ($services instanceof IDiscoveryClient)
        {
            $this->discoveryClient = $services;
        }
        else
        {
            $this->services = $services;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getInstances(): array
    {
        if ($this->discoveryClient)
        {
            return $this->discoveryClient->getInstances();
        }
        else
        {
            return $this->services;
        }
    }

    public function getDiscoveryClient(): ?IDiscoveryClient
    {
        return $this->discoveryClient;
    }
}
