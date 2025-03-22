<?php

namespace AppModules\Trades\Services;

use AppModules\Trades\Concerns\Enums\TradeStatusEnum;
use AppModules\Trades\DTO\TradesDTO;
use AppModules\Trades\Events\TradeCreatedEvent;
use AppModules\Trades\Events\TradeExecutedEvent;
use AppModules\Trades\Repositories\TradesRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

readonly class TradesService
{
    public function __construct(private TradesRepository $repository) {}

    public function createTrade(array $data): TradesDTO
    {
        $tradeDto = $this->repository->create($data);

        broadcast(new TradeCreatedEvent($tradeDto));

        return $tradeDto;
    }

    public function executeTrade(int $tradeId): bool
    {
        $trade = $this->repository->getById($tradeId);

        if (! $trade || $trade->status != TradeStatusEnum::Pending) {
            throw new UnprocessableEntityHttpException('Pending trade not found');
        }

        $updated = $this->repository->update($tradeId, ['status' => TradeStatusEnum::Executed->value]);

        if ($updated) {
            $trade = $this->repository->getById($tradeId);
            broadcast(new TradeExecutedEvent($trade));
        }

        return $this->repository->update($tradeId, ['status' => TradeStatusEnum::Executed->value]);
    }
}
