<?php

declare(strict_types=1);

namespace Imi\Service\LoadBalancer\Contract;

use Imi\Service\Contract\IService;
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
