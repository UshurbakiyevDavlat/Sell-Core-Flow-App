<?php

namespace AppModules\Assets\Services;

use AppModules\Assets\DTO\AssetDTO;
use AppModules\Assets\Repositories\AssetRepository;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Junges\Kafka\Facades\Kafka;

class AssetService
{
    public function __construct(
        private readonly AssetRepository $repository
    ) {}

    public function getAll(int $perPage): LengthAwarePaginator
    {
        return $this->repository->getAll($perPage);
    }

    public function getById(int $id): ?AssetDTO
    {
        return $this->repository->getById($id);
    }

    public function create(array $data): AssetDTO
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): ?AssetDTO
    {
        return $this->repository->update($id, $data);
    }

    /**
     * @throws Exception
     */
    public function updatePrice(int $id, float $newPrice): void
    {
        DB::transaction(function () use ($id, $newPrice) {
            $this->repository->update($id, ['price' => $newPrice]);

            Kafka::publish()
                ->onTopic('price_update')
                ->withBody([
                    'asset_id' => $id,
                    'price' => $newPrice,
                ])
                ->send();
        });
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
