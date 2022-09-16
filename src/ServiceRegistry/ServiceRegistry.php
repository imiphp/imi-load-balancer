<?php

declare(strict_types=1);

namespace Imi\Service\ServiceRegistry;

use Imi\Bean\Annotation\Bean;
use Imi\Log\Log;
use Imi\Server\Contract\IServer;
use Imi\Server\ServerManager;
use Imi\Service\Service;
use Imi\Service\ServiceRegistry\Contract\IServiceRegistry;
use Imi\Util\System;

/**
 * @Bean("ServiceRegistry")
 */
class ServiceRegistry
{
    /**
     * 驱动列表
     * [[
     *     'driver' => XXXServiceRegistry::class, // 驱动类名
     *     // 注册的服务列表
     *     // 可以传服务器名，默认留空是全部服务器（包括主+子）
     *     'services' => [
     *         'main', // 主服务器是 main，子服务器就是子服务器名。只写服务器名代表其它参数都是自动。
     *         // 数组配置
     *         [
     *             // 所有参数按需设置
     *             'server' => '服务器名', // 主服务器是 main，子服务器就是子服务器名
     *             'instanceId' => '实例ID',
     *             'serviceId' => '服务ID',
     *             'weight' => 1, // 权重
     *             'uri' => 'tcp://127.0.0.1:8080', // uri
     *             'host' => '127.0.0.1',
     *             'port' => 8080,
     *             'metadata' => [],
     *             'interface' => 'eth0', // 网卡 interface 名，自动获取当前网卡IP时有效
     *         ],
     *     ],
     *     'client' => [
     *         // 注册中心客户端连接配置，每个驱动不同
     *     ],
     * ]].
     */
    protected array $drivers = [];

    /**
     * 失败重试延迟，单位：秒.
     */
    protected float $failedSleep = 10;

    private array $driverServices = [];

    /**
     * Get 驱动列表.
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }

    /**
     * 自动注册.
     */
    public function register(): void
    {
        $servers = ServerManager::getServers();
        while (true)
        {
            try
            {
                foreach ($this->drivers as $index => $driverConfig)
                {
                    if (isset($this->driverServices[$index]))
                    {
                        continue;
                    }
                    $driverServicesItem = [
                        'services' => [],
                    ];
                    /** @var IServiceRegistry $driver */
                    $driverServicesItem['driver'] = $driver = new $driverConfig['driver']($driverConfig);
                    if ($servicesConfig = $driverConfig['services'] ?? [])
                    {
                        foreach ($servicesConfig as $value)
                        {
                            if (\is_array($value))
                            {
                                $serverName = $value['server'] ?? '';
                                $serverConfig = $value;
                            }
                            else
                            {
                                $serverName = $value;
                                $serverConfig = [];
                            }
                            if (!isset($servers[$serverName]))
                            {
                                continue;
                            }
                            $driver->register($driverServicesItem['services'][] = $this->makeService($servers[$serverName], $serverConfig));
                        }
                    }
                    else
                    {
                        foreach ($servers as $server)
                        {
                            $driver->register($driverServicesItem['services'][] = $this->makeService($server));
                        }
                    }
                    $this->driverServices[$index] = $driverServicesItem;
                }
                break;
            }
            catch (\Throwable $th)
            {
                Log::error($th);
                Log::warning(sprintf('Service registration failed, wait %.3f seconds and retry automatically', $this->failedSleep));
                usleep((int) ($this->failedSleep * 1000_000));
            }
        }
    }

    public function deregister(): void
    {
        $driverServices = $this->driverServices;
        $this->driverServices = [];
        foreach ($driverServices as $driverServicesItem)
        {
            foreach ($driverServicesItem['services'] as $service)
            {
                $driverServicesItem['driver']->deregister($service);
            }
        }
    }

    protected function makeService(IServer $server, array $serverConfig = []): Service
    {
        $metadata = $serverConfig['metadata'] ?? [];
        if (!isset($metadata['serverName']))
        {
            $metadata['serverName'] = $server->getName();
        }
        if (isset($serverConfig['uri']))
        {
            return new Service($serverConfig['instanceId'] ?? '', $serverConfig['serviceId'] ?? $server->getName(), $serverConfig['uri'], $serverConfig['weight'] ?? 1, $metadata);
        }
        else
        {
            if (isset($serverConfig['host']))
            {
                $host = $serverConfig['host'];
            }
            else
            {
                $host = $this->getHost($serverConfig['interface'] ?? '');
            }

            return Service::make($serverConfig['instanceId'] ?? '', $serverConfig['serviceId'] ?? $server->getName(), $host,
                $serverConfig['port'] ?? $server->getConfig()['port'] ?? 0 // TODO: 在 imi 3.0 IServer 要增加 getHost()、getPort()
                , $serverConfig['weight'] ?? 1, $metadata
            );
        }
    }

    protected function getHost(string $interface = ''): string
    {
        $ips = System::netLocalIp();
        if ('' === $interface)
        {
            if ($ips)
            {
                return reset($ips) ?: '';
            }
            else
            {
                $hostname = gethostname();
                if (false === $hostname)
                {
                    return '';
                }

                return gethostbyname($hostname);
            }
        }
        elseif (isset($ips[$interface]))
        {
            return $ips[$interface];
        }

        return '';
    }
}
