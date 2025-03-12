<?php

namespace AppModules\Strategies\Console;

use AppModules\Strategies\Services\StrategyService;
use Exception;
use Illuminate\Console\Command;

class TestStrategyCommand extends Command
{
    protected $signature = 'strategies:test {strategy} {--preset=default}';
    protected $description = 'Test a trading strategy with dummy market data';

    public function __construct(
        protected StrategyService $strategyService
    ) {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $strategy = $this->argument('strategy');
        $preset = $this->option('preset');

        $marketData = $this->generateMarketData($preset);

        $this->info("🎯 Testing strategy: $strategy");
        $this->info("📊 Generated data range: " . min($marketData) . " to " . max($marketData));

        // Вычисляем предыдущие SMA
        $previousSma50 = array_sum(array_slice($marketData, -51, 50)) / 50;
        $previousSma200 = array_sum(array_slice($marketData, -201, 200)) / 200;

        $order = $this->strategyService->runStrategy($strategy, $marketData, $previousSma50, $previousSma200);

        if (!$order) {
            $this->warn('❌ Strategy did not generate a trade.');
        } else {
            $this->info('✅ Strategy successfully generated an order:');
            dump($order);
        }
    }

    private function generateMarketData(string $preset): array
    {
        return match ($preset) {
            'bullish_strong' => $this->generateSmoothTrend(60, 120, 250, 5),
            'bullish_weak' => $this->generateSmoothTrend(80, 110, 250, 4),
            'bearish_strong' => $this->generateSmoothTrend(120, 60, 250, 5),
            'bearish_weak' => $this->generateSmoothTrend(110, 80, 250, 4),
            'volatile' => $this->generateVolatileTrend(60, 540, 250, 120),
            'extreme' => $this->generateExtremeTrend(60, 540, 250),
            'neutral' => array_fill(0, 250, 100),
            default => array_map(fn() => rand(80, 120), range(1, 250))
        };
    }

    private function generateExtremeTrend(int $min, int $max, int $length): array
    {
        $data = [];
        for ($i = 0; $i < $length; $i++) {
            if ($i < $length / 4) {
                $data[] = rand($min, $min + 20); // Резкое падение
            } elseif ($i < $length / 2) {
                $data[] = rand($max - 20, $max); // Резкий рост
            } elseif ($i < 3 * $length / 4) {
                $data[] = rand($min, $min + 30); // Снова падение
            } else {
                $data[] = rand($max - 30, $max); // Сильный рост
            }
        }
        return $data;
    }


    private function generateSmoothTrend(int $start, int $end, int $length, int $volatility): array
    {
        $step = ($end - $start) / $length;
        $trend = [];

        for ($i = 0; $i < $length; $i++) {
            $trend[] = $start + ($i * $step) + rand(-$volatility, $volatility);
        }

        return $trend;
    }

    private function generateVolatileTrend(int $min, int $max, int $length, int $volatility): array
    {
        $trend = [];
        $price = rand($min, $max);

        for ($i = 0; $i < $length; $i++) {
            $price += rand(-$volatility, $volatility);
            $price = max($min, min($max, $price)); // Ограничиваем диапазон
            $trend[] = $price;
        }

        return $trend;
    }

}
