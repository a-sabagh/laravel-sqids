<?php

namespace LaravelSqids\Contracts;

interface ManagerInterface
{
    public function driver(?string $name = null): object;

    public function getDriverConfig(?string $name = null): mixed;

    public function getDefaultDriver(): string;
}
