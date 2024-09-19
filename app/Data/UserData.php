<?php

namespace App\Data;

class UserData
{
    public function __construct(
        public int|string|null $id,
        public string $name,
        public string $email,
        public ?string $password = null
    )
    {}

    public function toArray() : array
    {
        return [
            'id' => $this->id,
            'name' =>$this->name,
            'email' => $this->email
        ];
    }



}
