<?php

declare(strict_types=1);

namespace Imi\Service\Test\LoadBalancer;

class RoundRobinLoadBalancerTest extends BaseLoadBalancerTest
{
    protected string $class = \Imi\Service\LoadBalancer\RoundRobinLoadBalancer::class;
}
