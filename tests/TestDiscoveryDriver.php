<?php

declare(strict_types=1);

namespace Imi\Service\Test;

use Imi\Service\Discovery\Contract\IDiscoveryDriver;

class TestDiscoveryDriver implements IDiscoveryDriver
{
    private array $instances = [];

    public function __construct(array $instances)
    {
        $this->instances = $instances;
    }

    /**
     * @return IService[]
     */
    public function getInstances(string $serviceId): array
    {
        return $this->instances;
    }
}
