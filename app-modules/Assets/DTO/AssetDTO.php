<?php

namespace AppModules\Assets\DTO;

use AppModules\Assets\Models\Asset;
use Carbon\Carbon;

readonly class AssetDTO
{
    public function __construct(
        public int $id,
        public string $symbol,
        public string $name,
        public string $type,
        public float $price,
        public Carbon $createdAt,

    ) {}

    public static function fromModel(Asset $asset): self
    {
        return new self(
            id: $asset->id,
            symbol: $asset->symbol,
            name: $asset->name,
            type: $asset->type->value,
            price: $asset->price,
            createdAt: $asset->created_at
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'symbol' => $this->symbol,
            'name' => $this->name,
            'type' => $this->type,
            'price' => $this->price,
            'createdAt' => $this->createdAt,
        ];
    }
}
