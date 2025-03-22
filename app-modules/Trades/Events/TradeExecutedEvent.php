<?php

namespace AppModules\Trades\Events;

use AppModules\Trades\DTO\TradesDTO;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TradeExecutedEvent implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public TradesDTO $tradeDto) {}

    public function broadcastOn(): Channel
    {
        return new Channel('trades');
    }

    public function broadcastWith(): array
    {
        return [
            'trade' => $this->tradeDto,
        ];
    }

    public function broadcastAs(): string
    {
        return 'trade.executed';
    }
}
