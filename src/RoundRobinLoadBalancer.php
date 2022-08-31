<?php

declare(strict_types=1);

namespace Imi\LoadBalancer;

use Imi\LoadBalancer\Contract\BaseLoadBalancer;
use Imi\LoadBalancer\Contract\IService;

/**
 * 轮询-负载均衡
 */
class RoundRobinLoadBalancer extends BaseLoadBalancer
{
    private int $position = 0;

    public function choose(): ?IService
    {
        $maxIndex = $this->services->count() - 1;
        $position = &$this->position;
        if (++$position > $maxIndex)
        {
            $position = 0;
        }

        return $this->services[$position] ?? null;
    }
}
