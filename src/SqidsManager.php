<?php

namespace LaravelSqids;

use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;
use LaravelSqids\Contracts\ManagerInterface;

class SqidsManager implements ManagerInterface
{
    protected $container;

    protected $config;

    protected $drivers = [];

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->config = $container->make('config');
    }

    public function getDefaultDriver(): string
    {
        return $this->config->get('sqids.default', 'default');
    }

    public function getDriverConfig(?string $driver = null): array
    {
        $driver = $driver ?: $this->getDefaultDriver();

        return $this->config->get("sqids.drivers.{$driver}");
    }

    public function driver(?string $driver = null): object
    {
        $driver = $driver ?: $this->getDefaultDriver();

        if (is_null($driver)) {
            throw new InvalidArgumentException(sprintf(
                'Unable to resolve NULL Sqids driver for [%s].', static::class
            ));
        }

        if (! isset($this->drivers[$driver])) {
            $this->drivers[$driver] = $this->createDriver($driver);
        }

        return $this->drivers[$driver];
    }

    public function createDriver($driver): object
    {
        $config = $this->getDriverConfig($driver);

        if (is_null($config)) {
            throw new InvalidArgumentException(sprintf(
                'Sqids Driver [%s] is not defined in sqids configuration.', $driver
            ));
        }

        return new SqidsAdapter(...$config);
    }
}
