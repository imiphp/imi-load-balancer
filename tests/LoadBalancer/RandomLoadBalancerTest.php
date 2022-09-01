<?php

declare(strict_types=1);

namespace Imi\Service\Test\LoadBalancer;

class RandomLoadBalancerTest extends BaseLoadBalancerTest
{
    protected string $class = \Imi\Service\LoadBalancer\RandomLoadBalancer::class;
}
