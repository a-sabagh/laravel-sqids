<?php

namespace LaravelSqids;

use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;
use LaravelSqids\Contracts\ManagerInterface;

class SqidsManager implements ManagerInterface
{
    protected $container;

    protected $config;

    protected array $extensions = [];

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

    protected function callExtension($driver): object
    {
        return $this->extensions[$driver]($this->container);
    }

    public function extend(string $name, callable $resolver): void
    {
        if (isset($this->extensions[$name])) {
            throw new InvalidArgumentException(sprintf(
                'Sqids driver [%s] is already registered.', $name
            ));
        }

        $this->extensions[$name] = $resolver;
    }

    public function createDriver($driver): object
    {
        if (isset($this->extensions[$driver])) {
            return $this->callExtension($driver);
        }

        $config = $this->getDriverConfig($driver);

        if (is_null($config)) {
            throw new InvalidArgumentException(sprintf(
                'Sqids Driver [%s] is not defined in sqids configuration.', $driver
            ));
        }

        return new SqidsAdapter(...$config);
    }
}
