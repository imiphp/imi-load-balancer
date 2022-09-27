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

    /**
     * {@inheritDoc}
     */
    public function __construct($services)
    {
        parent::__construct($services);
        if (($count = \count($this->getInstances())) > 0)
        {
            $this->position = mt_rand(0, $count - 1);
        }
    }

    public function choose(): ?IService
    {
        $maxIndex = \count($services = $this->getInstances()) - 1;
        $position = &$this->position;
        if (++$position > $maxIndex)
        {
            $position = 0;
        }

        return $services[$position] ?? null;
    }
}
