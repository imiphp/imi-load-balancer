<?php

declare(strict_types=1);

namespace Imi\Service\Test\LoadBalancer;

class WeightLoadBalancerTest extends BaseLoadBalancerTest
{
    protected string $class = \Imi\Service\LoadBalancer\WeightLoadBalancer::class;
}
