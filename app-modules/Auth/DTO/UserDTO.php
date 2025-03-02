<?php

namespace AppModules\Auth\DTO;

use AppModules\Auth\Models\User;

class UserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $password,
        public string $status
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
