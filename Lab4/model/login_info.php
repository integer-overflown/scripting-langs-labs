<?php

class LoginInfo
{
    private const SESSION_KEY_LOGIN_INFO = 'LOGIN_INFO';

    public function __construct(
        public string $login,
        public int    $timestamp
    )
    {
    }

    public static function fromSession(): ?LoginInfo
    {
        return session_status() !== PHP_SESSION_NONE && array_key_exists(static::SESSION_KEY_LOGIN_INFO, $_SESSION)
            ? $_SESSION[static::SESSION_KEY_LOGIN_INFO]
            : null;
    }
}
