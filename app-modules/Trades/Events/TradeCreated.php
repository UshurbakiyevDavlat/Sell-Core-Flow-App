<?php

namespace AppModules\Trades\Events;

use AppModules\Trades\DTO\TradesDTO;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TradeCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public TradesDTO $tradeDto)
    {
    }

    public function toArray(): array
    {
        return $this->tradeDto->toArray();
    }
}
