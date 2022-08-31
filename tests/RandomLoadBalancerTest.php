<?php

declare(strict_types=1);

namespace Imi\LoadBalancer\Test;

class RandomLoadBalancerTest extends BaseLoadBalancerTest
{
    protected string $class = \Imi\LoadBalancer\RandomLoadBalancer::class;
}
