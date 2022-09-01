<?php

declare(strict_types=1);

namespace Imi\Service;

use Imi\Service\Contract\IService;
use Imi\Util\Uri;

/**
 * 默认服务实例.
 */
class Service implements IService
{
    protected string $instanceId = '';

    protected string $serviceId = '';

    protected float $weight = 0;

    protected ?Uri $uri = null;

    protected array $metadata = [];

    /**
     * @param string|\Imi\Util\Uri $uri
     */
    public function __construct(string $instanceId, string $serviceId, $uri, float $weight = 0, array $metadata = [])
    {
        $this->instanceId = $instanceId;
        $this->serviceId = $serviceId;
        $this->weight = $weight;
        $this->uri = (($uri instanceof Uri) ? $uri : new Uri($uri));
        $this->metadata = $metadata;
    }

    public static function make(string $instanceId, string $serviceId, string $host, int $port, float $weight = 0, array $metadata = []): self
    {
        $instance = new self($instanceId, $serviceId, Uri::makeUri($host, $metadata['path'] ?? '', $metadata['query'] ?? '', $port, $metadata['scheme'] ?? 'http', $metadata['fragment'] ?? '', $metadata['userInfo'] ?? ''), $weight, $metadata);

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function getInstanceId(): string
    {
        return $this->instanceId;
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    /**
     * 权重.
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * {@inheritDoc}
     */
    public function getUri(): Uri
    {
        return $this->uri;
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
