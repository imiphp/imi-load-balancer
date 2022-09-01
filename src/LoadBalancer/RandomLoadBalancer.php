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
        $count = $this->services->count();
        if ($count > 0)
        {
            return $this->services[mt_rand(0, $this->services->count() - 1)] ?? null;
        }
        else
        {
            return null;
        }
    }
}
