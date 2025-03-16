<?php

namespace AppModules\Assets\Providers;

use AppModules\Assets\Concerns\Enums\AssetIntervalEnum;
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
        $this->apiKey = $config['api_key'] ?? throw new InvalidArgumentException('API Key is required');
        $this->baseUrl = $config['base_url'] ?? 'https://www.alphavantage.co/query';
    }

    public function getAssetList(): array
    {
        return Cache::remember(
            'market_asset_list',
            300,
            function () {
                $url = "$this->baseUrl?function=LISTING_STATUS&apikey=$this->apiKey";
                $response = Http::get($url)->body();

                $lines = explode("\n", $response);
                $headers = str_getcsv(array_shift($lines));

                $assets = [];
                foreach ($lines as $line) {
                    if (empty(trim($line))) {
                        continue;
                    }
                    $data = str_getcsv($line);
                    $assets[] = array_combine($headers, $data);
                }

                return $assets;
            }
        );
    }

    public function getPrice(string $symbol): float
    {
        // todo для популярных активов можно сделать меньший ttl
        return Cache::remember("market_price_$symbol", 300, function () use ($symbol) {
            $url = "$this->baseUrl?function=GLOBAL_QUOTE&symbol=$symbol&apikey=$this->apiKey";
            $response = Http::get($url)->json();

            return (float) ($response['Global Quote']['05. price'] ?? 0);
        });
    }

    public function getHistoricalData(
        string $symbol,
        AssetIntervalEnum $interval = AssetIntervalEnum::Daily,
        int $outputSize = 100
    ): array {
        return $this->fakeHistoricalData();
        //        $function = match ($interval) {
        //            AssetIntervalEnum::Daily => 'TIME_SERIES_DAILY',
        //            AssetIntervalEnum::Weekly => 'TIME_SERIES_WEEKLY',
        //            AssetIntervalEnum::Monthly => 'TIME_SERIES_MONTHLY',
        //        };

        //        return Cache::remember("market_historical_data_$interval->value", 300,
        //            function () use ($symbol, $function, $outputSize, $interval) {
        //                $url = "$this->baseUrl?function=$function&symbol=$symbol&apikey=$this->apiKey&outputsize=full";
        //                $response = Http::get($url)->json();
        //
        //                $key = match ($interval) {
        //                    AssetIntervalEnum::Daily => 'Time Series (Daily)',
        //                    AssetIntervalEnum::Weekly => 'Weekly Time Series',
        //                    AssetIntervalEnum::Monthly => 'Monthly Time Series',
        //                };
        //
        //                $data = $response[$key] ?? [];
        //
        //                // Ограничиваем количество возвращаемых записей
        //                return array_slice($data, 0, $outputSize);
        //            }
        //        );
    }

    private function fakeHistoricalData(): array
    {
        return [
            '2025-03-14' => [
                '1. open' => '211.2500',
                '2. high' => '213.9500',
                '3. low' => '209.5800',
                '4. close' => '213.4900',
                '5. volume' => '60107582',
            ],
            '2025-03-13' => [
                '1. open' => '215.9500',
                '2. high' => '216.8394',
                '3. low' => '208.4200',
                '4. close' => '209.6800',
                '5. volume' => '61368330',
            ],
            '2025-03-12' => [
                '1. open' => '220.1400',
                '2. high' => '221.7500',
                '3. low' => '214.9100',
                '4. close' => '216.9800',
                '5. volume' => '62547467',
            ],
            '2025-03-11' => [
                '1. open' => '223.8050',
                '2. high' => '225.8399',
                '3. low' => '217.4500',
                '4. close' => '220.8400',
                '5. volume' => '76137410',
            ],
            '2025-03-10' => [
                '1. open' => '235.5400',
                '2. high' => '236.1600',
                '3. low' => '224.2200',
                '4. close' => '227.4800',
                '5. volume' => '71451281',
            ],
            '2025-03-07' => [
                '1. open' => '235.1050',
                '2. high' => '241.3700',
                '3. low' => '234.7600',
                '4. close' => '239.0700',
                '5. volume' => '46273565',
            ],
            '2025-03-06' => [
                '1. open' => '234.4350',
                '2. high' => '237.8600',
                '3. low' => '233.1581',
                '4. close' => '235.3300',
                '5. volume' => '45170419',
            ],
            '2025-03-05' => [
                '1. open' => '235.4200',
                '2. high' => '236.5500',
                '3. low' => '229.2300',
                '4. close' => '235.7400',
                '5. volume' => '47227643',
            ],
            '2025-03-04' => [
                '1. open' => '237.7050',
                '2. high' => '240.0700',
                '3. low' => '234.6800',
                '4. close' => '235.9300',
                '5. volume' => '53798062',
            ],
            '2025-03-03' => [
                '1. open' => '241.7900',
                '2. high' => '244.0272',
                '3. low' => '236.1120',
                '4. close' => '238.0300',
                '5. volume' => '47183985',
            ],
            '2025-02-28' => [
                '1. open' => '236.9500',
                '2. high' => '242.0900',
                '3. low' => '230.2000',
                '4. close' => '241.8400',
                '5. volume' => '56833360',
            ],
            '2025-02-27' => [
                '1. open' => '239.4100',
                '2. high' => '242.4600',
                '3. low' => '237.0600',
                '4. close' => '237.3000',
                '5. volume' => '41153639',
            ],
            '2025-02-26' => [
                '1. open' => '244.3300',
                '2. high' => '244.9800',
                '3. low' => '239.1300',
                '4. close' => '240.3600',
                '5. volume' => '44433564',
            ],
            '2025-02-25' => [
                '1. open' => '248.0000',
                '2. high' => '250.0000',
                '3. low' => '244.9100',
                '4. close' => '247.0400',
                '5. volume' => '48013272',
            ],
            '2025-02-24' => [
                '1. open' => '244.9250',
                '2. high' => '248.8600',
                '3. low' => '244.4200',
                '4. close' => '247.1000',
                '5. volume' => '51326396',
            ],
            '2025-02-21' => [
                '1. open' => '245.9500',
                '2. high' => '248.6900',
                '3. low' => '245.2200',
                '4. close' => '245.5500',
                '5. volume' => '53197431',
            ],
            '2025-02-20' => [
                '1. open' => '244.9400',
                '2. high' => '246.7800',
                '3. low' => '244.2900',
                '4. close' => '245.8300',
                '5. volume' => '32316907',
            ],
            '2025-02-19' => [
                '1. open' => '244.6600',
                '2. high' => '246.0100',
                '3. low' => '243.1604',
                '4. close' => '244.8700',
                '5. volume' => '32204215',
            ],
            '2025-02-18' => [
                '1. open' => '244.1500',
                '2. high' => '245.1800',
                '3. low' => '241.8400',
                '4. close' => '244.4700',
                '5. volume' => '48822491',
            ],
            '2025-02-14' => [
                '1. open' => '241.2500',
                '2. high' => '245.5500',
                '3. low' => '240.9900',
                '4. close' => '244.6000',
                '5. volume' => '40896227',
            ],
            '2025-02-13' => [
                '1. open' => '236.9100',
                '2. high' => '242.3399',
                '3. low' => '235.5700',
                '4. close' => '241.5300',
                '5. volume' => '53614054',
            ],
            '2025-02-12' => [
                '1. open' => '231.2000',
                '2. high' => '236.9600',
                '3. low' => '230.6800',
                '4. close' => '236.8700',
                '5. volume' => '45243292',
            ],
            '2025-02-11' => [
                '1. open' => '228.2000',
                '2. high' => '235.2300',
                '3. low' => '228.1300',
                '4. close' => '232.6200',
                '5. volume' => '53718362',
            ],
            '2025-02-10' => [
                '1. open' => '229.5700',
                '2. high' => '230.5850',
                '3. low' => '227.2000',
                '4. close' => '227.6500',
                '5. volume' => '33115645',
            ],
        ];

    }
}
