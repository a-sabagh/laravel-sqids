<?php

namespace LaravelSqids\Contracts;

interface ManagerInterface
{
    public function driver(?string $name = null): object;

    public function getDriverConfig(?string $name = null): array;

    public function getDefaultDriver(): string;

    public function extend(string $name, callable $resolver): void;
}
