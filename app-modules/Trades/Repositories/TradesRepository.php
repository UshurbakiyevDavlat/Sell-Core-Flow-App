<?php

namespace AppModules\Trades\Repositories;

use AppModules\Trades\DTO\TradesDTO;
use AppModules\Trades\Models\Trade;

class TradesRepository
{
    //todo тут неявный аргумент массива приходит, лучше сделать экшн DTO и передавать его сюда
    public function create(array $data): TradesDTO
    {
        $trade = Trade::query()->create($data);

        return TradesDTO::fromModel($trade);
    }

    public function getById(int $id): ?TradesDTO
    {
        $trade = Trade::query()->find($id);
        if (!$trade) {
            return null;
        }

        return TradesDTO::fromModel($trade);
    }

    //todo такая же история c аргументом как выше
    public function update(int $id, array $data): bool
    {
        return Trade::query()->where('id', $id)->update($data);
    }
}
