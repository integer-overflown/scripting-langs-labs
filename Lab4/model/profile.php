<?php

class Profile
{
    public const NAME_PATTERN = '\w{1,}';
    public const SURNAME_PATTERN = '\w{1,}';
    public const MIN_ALLOWED_AGE = 16;
    public const KEY_NAME = 'name';
    public const KEY_SURNAME = 'surname';
    public const KEY_BIRTH_DATE = 'birthDate';
    public const PHOTO_TYPE_MIME = 'image/*';
    public const KEY_PROFILE_PICTURE = 'picture';

    private const SESSION_KEY_PROFILE = 'USER_PROFILE';

    private string $name;
    private string $surname;
    private int $birthDate;
    private ?string $picturePath;

    public function __serialize(): array
    {
        return [
            static::KEY_NAME => $this->name,
            static::KEY_SURNAME => $this->surname,
            static::KEY_BIRTH_DATE => $this->birthDate
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data[static::KEY_NAME];
        $this->surname = $data[static::KEY_SURNAME];
        $this->birthDate = intval($data[static::KEY_BIRTH_DATE]);
    }

    public static function fromSession(): ?LoginInfo
    {
        return session_status() !== PHP_SESSION_NONE && array_key_exists(static::SESSION_KEY_PROFILE, $_SESSION)
            ? unserialize($_SESSION[static::SESSION_KEY_PROFILE])
            : null;
    }

    public function writeToSession(): void
    {
        $_SESSION[static::SESSION_KEY_PROFILE] = serialize($this);
    }

    public function fromKeyArray(array $keyArray): void
    {
        $this->__unserialize($keyArray);
    }

    public function isValid(): bool
    {
        return !(empty($this->name) || empty($this->surname) || $this->birthDate <= 0);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getBirthDate(): int
    {
        return $this->birthDate;
    }

    public function setName(string $name): Profile
    {
        $this->name = $name;
        return $this;
    }

    public function setSurname(string $surname): Profile
    {
        $this->surname = $surname;
        return $this;
    }

    public function setBirthDate(int $birthDate): Profile
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getPicturePath(): ?string
    {
        return $this->picturePath;
    }

    public function setPicturePath(string $picturePath): Profile
    {
        $this->picturePath = $picturePath;
        return $this;
    }
}
