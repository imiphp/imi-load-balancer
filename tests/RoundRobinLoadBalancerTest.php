<?php

declare(strict_types=1);

namespace Imi\LoadBalancer\Test;

class RoundRobinLoadBalancerTest extends BaseLoadBalancerTest
{
    protected string $class = \Imi\LoadBalancer\RoundRobinLoadBalancer::class;
}
