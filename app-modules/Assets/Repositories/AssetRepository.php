<?php

namespace AppModules\Assets\Repositories;

use AppModules\Assets\DTO\AssetDTO;
use AppModules\Assets\Models\Asset;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class AssetRepository
{
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Cache::remember("assets_list_page_$perPage", 60, function () use ($perPage) {
            return Asset::query()->paginate($perPage)
                ->through(fn(Asset $asset) => AssetDTO::fromModel($asset));
        });
    }

    public function getById(int $id): ?AssetDTO
    {
        return Cache::remember("asset_$id", 60, function () use ($id) {
            $asset = Asset::query()->find($id);
            return $asset ? AssetDTO::fromModel($asset) : null;
        });
    }

    public function create(array $data): AssetDTO
    {
        $asset = Asset::query()->create($data);
        Cache::forget("assets_list_page_10");
        return AssetDTO::fromModel($asset);
    }

    public function update(int $id, array $data): ?AssetDTO
    {
        $asset = Asset::query()->find($id);
        if (!$asset) return null;

        $asset->update($data);
        Cache::forget("asset_$id");
        Cache::forget("assets_list_page_10");

        return AssetDTO::fromModel($asset);
    }

    public function delete(int $id): bool
    {
        $asset = Asset::query()->find($id);
        if (!$asset) return false;

        $asset->delete();
        Cache::forget("asset_$id");
        Cache::forget("assets_list_page_10");

        return true;
    }
}
