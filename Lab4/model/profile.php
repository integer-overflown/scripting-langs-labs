<?php

class Profile
{
    public const NAME_PATTERN = '\w{1,}';
    public const SURNAME_PATTERN = '\w{1,}';
    public const MIN_ALLOWED_AGE = 16;
    public const KEY_NAME = 'name';
    public const KEY_SURNAME = 'surname';
    public const KEY_BIRTH_DATE = 'birthDate';

    private string $name;
    private string $surname;
    private int $birthDate;

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
        $this->birthDate = $data[static::KEY_BIRTH_DATE];
    }

    public function toJson(): string
    {
        return json_encode($this->__serialize());
    }

    public function fromJson(string $json): bool
    {
        $payload = json_decode($json, true);

        if ($payload === null) {
            return false;
        }

        $this->__unserialize($payload);

        return false;
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
}
