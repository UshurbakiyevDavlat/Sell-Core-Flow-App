<?php

namespace AppModules\Auth\DTO;

use App\Concerns\Enums\Auth\UserStatusEnum;
use AppModules\Auth\Models\User;

readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $password,
        public UserStatusEnum $status
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            password: $user->password,
            status: $user->status
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status
        ];
    }
}
