<?php

declare(strict_types=1);

namespace Imi\Service\LoadBalancer\Contract;

use Imi\Service\Contract\IService;
use Imi\Util\ArrayList;

abstract class BaseLoadBalancer implements ILoadBalancer
{
    /**
     * @var ArrayList<IService>
     */
    protected ArrayList $services;

    /**
     * @param ArrayList<IService> $services
     */
    public function __construct(ArrayList $services)
    {
        $this->setServices($services);
    }

    public function setServices(ArrayList $services): void
    {
        $this->services = $services;
    }

    /**
     * {@inheritDoc}
     */
    public function getServices(): ArrayList
    {
        return $this->services;
    }
}
