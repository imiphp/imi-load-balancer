<?php

declare(strict_types=1);

namespace Imi\Service\Test\LoadBalancer;

use Imi\Service\Contract\IService;
use Imi\Service\LoadBalancer\Contract\ILoadBalancer;
use Imi\Service\Service;
use Imi\Util\ArrayList;
use PHPUnit\Framework\TestCase;

abstract class BaseLoadBalancerTest extends TestCase
{
    protected string $class;

    public function test(): void
    {
        $services = new ArrayList(IService::class, [
            new Service('a', 'imi', 'tcp://127.0.0.1:10001', 1, ['id' => 'a']),
            new Service('b', 'imi', 'tcp://127.0.0.1:10002', 2, ['id' => 'b']),
        ]);
        /** @var ILoadBalancer $loadBalancer */
        $loadBalancer = new $this->class($services);

        $this->assertEquals($services, $loadBalancer->getServices());

        $service = $loadBalancer->choose();
        $this->assertNotNull($service);

        $services = new ArrayList(IService::class, []);
        /** @var ILoadBalancer $loadBalancer */
        $loadBalancer = new $this->class($services);

        $this->assertEquals($services, $loadBalancer->getServices());

        $service = $loadBalancer->choose();
        $this->assertNull($service);
    }
}
