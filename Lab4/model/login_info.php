<?php

class LoginInfo
{
    public readonly int $timestamp;

    private const SESSION_KEY_LOGIN_INFO = 'LOGIN_INFO';

    public function __construct(
        public string $login
    )
    {
        $this->timestamp = time();
    }

    public static function fromSession(): ?LoginInfo
    {
        return session_status() !== PHP_SESSION_NONE && array_key_exists(static::SESSION_KEY_LOGIN_INFO, $_SESSION)
            ? $_SESSION[static::SESSION_KEY_LOGIN_INFO]
            : null;
    }

    public function writeToSession(): void
    {
        $_SESSION[static::SESSION_KEY_LOGIN_INFO] = $this;
    }
}
