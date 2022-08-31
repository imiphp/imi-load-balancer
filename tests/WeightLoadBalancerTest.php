<?php

declare(strict_types=1);

namespace Imi\LoadBalancer\Test;

class WeightLoadBalancerTest extends BaseLoadBalancerTest
{
    protected string $class = \Imi\LoadBalancer\WeightLoadBalancer::class;
}
