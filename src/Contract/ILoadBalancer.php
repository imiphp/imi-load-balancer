<?php

declare(strict_types=1);

namespace Imi\LoadBalancer\Contract;

use Imi\Util\ArrayList;

/**
 * 负载均衡接口.
 */
interface ILoadBalancer
{
    /**
     * @return ArrayList<IService>
     */
    public function getServices(): ArrayList;

    public function choose(): ?IService;
}
