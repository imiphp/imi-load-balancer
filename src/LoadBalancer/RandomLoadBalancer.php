<?php

declare(strict_types=1);

namespace Imi\Service\LoadBalancer;

use Imi\Service\Contract\IService;
use Imi\Service\LoadBalancer\Contract\BaseLoadBalancer;

/**
 * 随机-负载均衡
 */
class RandomLoadBalancer extends BaseLoadBalancer
{
    public function choose(): ?IService
    {
        if (($count = \count($services = $this->getServices())) > 0)
        {
            return $services[mt_rand(0, $count - 1)] ?? null;
        }
        else
        {
            return null;
        }
    }
}
