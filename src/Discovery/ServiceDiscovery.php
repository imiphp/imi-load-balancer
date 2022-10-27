<?php

declare(strict_types=1);

namespace Imi\Service\Discovery;

use Imi\App;
use Imi\Bean\Annotation\Bean;
use Imi\RequestContext;
use Imi\Service\Contract\IService;
use Imi\Service\Discovery\Contract\IDiscoveryClient;
use Imi\Service\Discovery\Contract\IDiscoveryDriver;
use Imi\Service\LoadBalancer\Contract\ILoadBalancer;
use Imi\Service\LoadBalancer\RandomLoadBalancer;

/**
 * @Bean("ServiceDiscovery")
 */
class ServiceDiscovery
{
    /**
     * 驱动列表
     * [[
     *     'driver' => \Imi\Service\Discovery\Contract\IDiscoveryDriver::class, // 服务发现驱动
     *     'client' => \Imi\Service\Discovery\DiscoveryClient::class, // 服务发现客户端
     *     'loadBalancer' => \Imi\Service\LoadBalancer\RandomLoadBalancer::class, // 负载均衡-随机
     *     // 'loadBalancer' => \Imi\Service\LoadBalancer\RoundRobinLoadBalancer::class, // 负载均衡-轮询
     *     // 'loadBalancer' => \Imi\Service\LoadBalancer\WeightLoadBalancer::class, // 负载均衡-权重
     *     // 发现服务列表
     *     'services' => [
     *         'main', // 服务名称
     *     ],
     *     'client' => [
     *         // 注册中心客户端连接配置，每个驱动不同
     *     ],
     *     'cacheTTL' => 60, // 缓存时间，单位：秒。默认为60秒，设为0不启用缓存
     * ]].
     */
    protected array $drivers = [];

    private array $serviceDriverIndexMap = [];

    public function __construct(array $drivers = [])
    {
        $this->drivers = $drivers;
    }

    public function __init(): void
    {
        foreach ($this->drivers as $index => $driverConfig)
        {
            foreach ($driverConfig['services'] ?? [] as $serviceId)
            {
                if (isset($this->serviceDriverIndexMap[$serviceId]))
                {
                    throw new \RuntimeException('Duplicate service name: ' . $serviceId);
                }
                $this->serviceDriverIndexMap[$serviceId] = $index;
            }
        }
    }

    /**
     * Get 驱动列表.
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }

    /**
     * 获取服务发现驱动.
     */
    public function getDiscoveryDriver(string $serviceId): IDiscoveryDriver
    {
        $context = RequestContext::getContext();
        if (isset($context[self::class]['discoveryDrivers'][$serviceId]))
        {
            return $context[self::class]['discoveryDrivers'][$serviceId];
        }
        if (!isset($this->serviceDriverIndexMap[$serviceId]))
        {
            throw new \RuntimeException(sprintf('Service [%s] does not exist', $serviceId));
        }
        $configItem = $this->drivers[$this->serviceDriverIndexMap[$serviceId]];
        if (!isset($configItem['driver']))
        {
            throw new \RuntimeException('ServiceDiscovery Missing configuration entry driver');
        }

        return $context[self::class]['discoveryDrivers'][$serviceId] = App::newInstance($configItem['driver'], $configItem);
    }

    /**
     * 获取服务发现客户端.
     */
    public function getDiscoveryClient(string $serviceId): IDiscoveryClient
    {
        $context = RequestContext::getContext();
        if (isset($context[self::class]['discoveryClients'][$serviceId]))
        {
            return $context[self::class]['discoveryClients'][$serviceId];
        }
        if (!isset($this->serviceDriverIndexMap[$serviceId]))
        {
            throw new \RuntimeException(sprintf('Service [%s] does not exist', $serviceId));
        }
        $configItem = $this->drivers[$this->serviceDriverIndexMap[$serviceId]];
        $class = $configItem['client'] ?? DiscoveryClient::class;

        return $context[self::class]['discoveryClients'][$serviceId] = App::newInstance($class, $serviceId, $this->getDiscoveryDriver($serviceId), $configItem);
    }

    /**
     * 获取负载均衡器.
     */
    public function getLoadBalancer(string $serviceId): ILoadBalancer
    {
        $context = RequestContext::getContext();
        if (isset($context[self::class]['loadBalancers'][$serviceId]))
        {
            return $context[self::class]['loadBalancers'][$serviceId];
        }
        $loadBalancer = $this->drivers[$this->serviceDriverIndexMap[$serviceId]]['loadBalancer'] ?? RandomLoadBalancer::class;

        return $context[self::class]['loadBalancers'][$serviceId] = App::newInstance($loadBalancer, $this->getDiscoveryClient($serviceId));
    }

    /**
     * 获取指定服务的实例.
     */
    public function getInstance(string $serviceId): ?IService
    {
        return $this->getLoadBalancer($serviceId)->choose();
    }
}
