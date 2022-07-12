<?php

class LoginInfo
{
    private int $timestamp;

    private const SESSION_KEY_LOGIN_INFO = 'LOGIN_INFO';

    public function __construct(
        private string $login
    )
    {
        $this->timestamp = time();
    }

    public static function fromSession(): ?LoginInfo
    {
        error_log('session status: ' . session_status() . ' key exist: ' . array_key_exists(static::SESSION_KEY_LOGIN_INFO, $_SESSION));
        return session_status() !== PHP_SESSION_NONE && array_key_exists(static::SESSION_KEY_LOGIN_INFO, $_SESSION)
            ? unserialize($_SESSION[static::SESSION_KEY_LOGIN_INFO])
            : null;
    }

    public function writeToSession(): void
    {
        $_SESSION[static::SESSION_KEY_LOGIN_INFO] = serialize($this);
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function __serialize(): array
    {
        return ['login' => $this->login, 'timestamp' => $this->timestamp];
    }

    public function __unserialize(array $data): void
    {
        $this->login = $data['login'];
        $this->timestamp = $data['timestamp'];
    }
}
