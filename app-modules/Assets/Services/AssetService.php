<?php

namespace AppModules\Assets\Services;

use AppModules\Assets\DTO\AssetDTO;
use AppModules\Assets\Repositories\AssetRepository;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
