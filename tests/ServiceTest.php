<?php

declare(strict_types=1);

namespace Imi\LoadBalancer\Test;

use Imi\LoadBalancer\Service;
use Imi\Util\Uri;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    public function test(): void
    {
        $service = new Service('imi-load-balancer', 'imi', 'tcp://127.0.0.1:1234', 19940312, ['a' => 123]);
        $this->assertEquals('imi-load-balancer', $service->getInstanceId());
        $this->assertEquals('imi', $service->getServiceId());
        $this->assertEquals('tcp://127.0.0.1:1234', $service->getUri()->__toString());
        $this->assertEquals(19940312, $service->getWeight());
        $this->assertEquals(['a' => 123], $service->getMetadata());

        $service = new Service('imi-load-balancer', 'imi', new Uri('tcp://127.0.0.1:1234'), 19940312, ['a' => 123]);
        $this->assertEquals('imi-load-balancer', $service->getInstanceId());
        $this->assertEquals('imi', $service->getServiceId());
        $this->assertEquals('tcp://127.0.0.1:1234', $service->getUri()->__toString());
        $this->assertEquals(19940312, $service->getWeight());
        $this->assertEquals(['a' => 123], $service->getMetadata());

        $service = Service::make('imi-load-balancer', 'imi', '127.0.0.1', 1234, 19940312, ['scheme' => 'tcp', 'a' => 123]);
        $this->assertEquals('imi-load-balancer', $service->getInstanceId());
        $this->assertEquals('imi', $service->getServiceId());
        $this->assertEquals('tcp://127.0.0.1:1234', $service->getUri()->__toString());
        $this->assertEquals(19940312, $service->getWeight());
        $this->assertEquals(['scheme' => 'tcp', 'a' => 123], $service->getMetadata());
    }
}
