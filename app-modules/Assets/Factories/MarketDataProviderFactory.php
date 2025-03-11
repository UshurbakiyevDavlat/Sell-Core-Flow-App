<?php

namespace AppModules\Assets\Factories;

use AppModules\Assets\Contracts\MarketDataProviderInterface;
use Illuminate\Support\Arr;
use RuntimeException;

/**
 * @mixin MarketDataProviderInterface
 */
readonly class MarketDataProviderFactory
{
    public function __construct(public array $config = [])
    {
    }

    public function __call(string $name, array $args)
    {
        return $this->provider()->{$name}(...$args);
    }

    public function config(?string $key = null): mixed
    {
        return Arr::get($this->config, $key);
    }

    public function provider(): MarketDataProviderInterface
    {
        $provider = $this->config('default');
        $providerConfig = $this->config("providers.$provider");

        if (empty($providerConfig)) {
            throw new RuntimeException("Market data provider [$provider] not found");
        }

        $client = $providerConfig['client'] ?? throw new RuntimeException("Market data provider [$provider] client not found");

        if (! class_exists($client)) {
            throw new RuntimeException("Market data provider [$provider] client does not exist");
        }

        if (! is_a($client, MarketDataProviderInterface::class, true)) {
            throw new RuntimeException("Market data provider [$provider] must implement " . MarketDataProviderInterface::class);
        }

        return new $client($providerConfig['config'] ?? []);
    }
}
