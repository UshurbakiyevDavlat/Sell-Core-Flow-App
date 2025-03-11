<?php

namespace AppModules\Assets\Providers;

use AppModules\Assets\Contracts\MarketDataProviderInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class AlphaVantageProvider implements MarketDataProviderInterface
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct(array $config)
    {
        $this->apiKey = $config['api_key'] ?? throw new InvalidArgumentException("API Key is required");
        $this->baseUrl = $config['base_url'] ?? 'https://www.alphavantage.co/query';
    }

    public function getAssetList(): array
    {
        $url = "$this->baseUrl?function=LISTING_STATUS&apikey=$this->apiKey";
        $response = Http::get($url)->body();

        $lines = explode("\n", $response);
        $headers = str_getcsv(array_shift($lines));

        $assets = [];
        foreach ($lines as $line) {
            if (empty(trim($line))) continue;
            $data = str_getcsv($line);
            $assets[] = array_combine($headers, $data);
        }

        return $assets;
    }

    public function getPrice(string $symbol): float
    {
        //todo для популярных активов можно сделать меньший ttl
        return Cache::remember("market_price_$symbol", 300, function () use ($symbol) {
            $url = "$this->baseUrl?function=GLOBAL_QUOTE&symbol=$symbol&apikey=$this->apiKey";
            $response = Http::get($url)->json();

            return (float)($response['Global Quote']['05. price'] ?? 0);
        });
    }

    public function getHistoricalData(string $symbol, string $interval = 'daily', int $outputSize = 100): array
    {
        $function = match ($interval) {
            'daily' => 'TIME_SERIES_DAILY',
            'weekly' => 'TIME_SERIES_WEEKLY',
            'monthly' => 'TIME_SERIES_MONTHLY',
            default => throw new InvalidArgumentException("Invalid interval: $interval"),
        };

        $url = "{$this->baseUrl}?function={$function}&symbol={$symbol}&apikey=$this->apiKey&outputsize=full";
        $response = Http::get($url)->json();

        $key = match ($interval) {
            'daily' => 'Time Series (Daily)',
            'weekly' => 'Weekly Time Series',
            'monthly' => 'Monthly Time Series',
        };

        $data = $response[$key] ?? [];

        // Ограничиваем количество возвращаемых записей
        return array_slice($data, 0, $outputSize);
    }
}
