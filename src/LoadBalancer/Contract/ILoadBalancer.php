<?php

declare(strict_types=1);

namespace Imi\Service\LoadBalancer\Contract;

use Imi\Service\Contract\IService;
use Imi\Service\Discovery\Contract\IDiscoveryClient;

/**
 * 负载均衡接口.
 */
interface ILoadBalancer
{
    /**
     * @return IService[]
     */
    public function getServices(): array;

    public function getDiscoveryClient(): ?IDiscoveryClient;

    public function choose(): ?IService;
}
