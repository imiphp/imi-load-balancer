<?php

declare(strict_types=1);

namespace Imi\Service\ServiceRegistry\Contract;

use Imi\Service\Contract\IService;

/**
 * 服务注册中心.
 */
interface IServiceRegistry
{
    /**
     * 注册服务
     */
    public function register(IService $service): void;

    /**
     * 注销服务
     */
    public function deregister(IService $service): void;
}
