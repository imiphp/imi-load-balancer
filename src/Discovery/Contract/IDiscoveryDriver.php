<?php

declare(strict_types=1);

namespace Imi\Service\Discovery\Contract;

use Imi\Service\Contract\IService;

/**
 * 服务发现驱动接口.
 */
interface IDiscoveryDriver
{
    /**
     * @return IService[]
     */
    public function getInstances(string $serviceId): array;
}
