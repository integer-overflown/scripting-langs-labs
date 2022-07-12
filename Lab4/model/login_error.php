<?php

class LoginError
{
    public function __construct(
        private readonly string $message
    )
    {
    }

    public function toJson(): string
    {
        return json_encode(['message' => $this->message]);
    }
}
