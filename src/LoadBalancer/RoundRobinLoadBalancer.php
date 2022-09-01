<?php

declare(strict_types=1);

namespace Imi\Service\LoadBalancer;

use Imi\Service\Contract\IService;
use Imi\Service\LoadBalancer\Contract\BaseLoadBalancer;

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
