<?php

namespace AppModules\Auth\Http\Resources;

use AppModules\Auth\DTO\UserDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin UserDTO */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
        ];
    }
}
