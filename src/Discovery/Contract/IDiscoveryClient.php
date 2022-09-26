<?php

declare(strict_types=1);

namespace Imi\Service\Discovery\Contract;

use Imi\Service\Contract\IService;

/**
 * 服务发现客户端接口.
 */
interface IDiscoveryClient
{
    /**
     * @return IService[]
     */
    public function getServices(): array;
}
