<?php

declare(strict_types=1);

namespace Imi\Service\LoadBalancer;

use Imi\Service\Contract\IService;
use Imi\Service\LoadBalancer\Contract\BaseLoadBalancer;
use Imi\Util\Random;

/**
 * 权重-负载均衡
 */
class WeightLoadBalancer extends BaseLoadBalancer
{
    public function choose(): ?IService
    {
        $weightSum = 0;
        foreach ($this->services as $service)
        {
            $weight = $service->getWeight();
            if ($weight > 0)
            {
                $weightSum += $weight;
            }
        }
        if ($weightSum <= 0)
        {
            return null;
        }
        $randomValue = Random::number(1, $weightSum);
        foreach ($this->services as $service)
        {
            $randomValue -= $service->getWeight();
            if ($randomValue <= 0)
            {
                return $service;
            }
        }

        return null;
    }
}
