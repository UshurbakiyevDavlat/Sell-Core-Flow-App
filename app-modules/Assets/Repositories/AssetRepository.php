<?php

namespace AppModules\Assets\Repositories;

use AppModules\Assets\DTO\AssetDTO;
use AppModules\Assets\Models\Asset;
use Illuminate\Pagination\LengthAwarePaginator;

class AssetRepository
{
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Asset::query()->paginate($perPage)->through(fn(Asset $asset) => AssetDTO::fromModel($asset));
    }

    public function getById(int $id): ?AssetDTO
    {
        $asset = Asset::query()->find($id);
        return $asset ? AssetDTO::fromModel($asset) : null;
    }

    public function create(array $data): AssetDTO
    {
        $asset = Asset::query()->create($data);
        return AssetDTO::fromModel($asset);
    }

    public function update(int $id, array $data): ?AssetDTO
    {
        $asset = Asset::query()->find($id);
        if (!$asset) return null;

        $asset->update($data);
        return AssetDTO::fromModel($asset);
    }

    public function delete(int $id): bool
    {
        $asset = Asset::query()->find($id);
        if (!$asset) return false;

        return $asset->delete();
    }
}
