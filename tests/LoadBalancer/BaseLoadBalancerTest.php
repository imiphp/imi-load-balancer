<?php

declare(strict_types=1);

namespace Imi\Service\Test\LoadBalancer;

use Imi\Service\Discovery\DiscoveryClient;
use Imi\Service\LoadBalancer\Contract\ILoadBalancer;
use Imi\Service\Service;
use PHPUnit\Framework\TestCase;

abstract class BaseLoadBalancerTest extends TestCase
{
    protected string $class;

    public function test(): void
    {
        $originServices = [
            new Service('a', 'imi', 'tcp://127.0.0.1:10001', 0.1, ['id' => 'a']),
            new Service('b', 'imi', 'tcp://127.0.0.1:10002', 0.2, ['id' => 'b']),
        ];
        /** @var ILoadBalancer $loadBalancer */
        $loadBalancer = new $this->class($originServices);

        $this->assertEquals($originServices, $loadBalancer->getServices());

        $service = $loadBalancer->choose();
        $this->assertNotNull($service);

        /** @var ILoadBalancer $loadBalancer */
        $loadBalancer = new $this->class([]);

        $this->assertEquals([], $loadBalancer->getServices());

        $service = $loadBalancer->choose();
        $this->assertNull($service);

        /** @var ILoadBalancer $loadBalancer */
        $loadBalancer = new $this->class(new DiscoveryClient($originServices));

        $this->assertEquals($originServices, $loadBalancer->getServices());

        $service = $loadBalancer->choose();
        $this->assertNotNull($service);
    }
}
