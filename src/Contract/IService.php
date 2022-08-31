<?php

declare(strict_types=1);

namespace Imi\LoadBalancer\Contract;

use Imi\Util\Uri;

/**
 * 服务实例接口.
 */
interface IService
{
    /**
     * 实例ID.
     */
    public function getInstanceId(): string;

    /**
     * 服务ID.
     */
    public function getServiceId(): string;

    /**
     * 权重.
     */
    public function getWeight(): float;

    public function getUri(): Uri;

    /**
     * 元数据.
     */
    public function getMetadata(): array;
}
