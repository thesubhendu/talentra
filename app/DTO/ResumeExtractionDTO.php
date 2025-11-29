<?php

namespace App\DTO;

class ResumeExtractionDTO
{
    public function __construct(
        public readonly string $text,
        public readonly ?string $error = null
    ) {}

    public function isSuccessful(): bool
    {
        return $this->error === null;
    }

    public static function success(string $text): self
    {
        return new self($text);
    }

    public static function failure(string $error): self
    {
        return new self('', $error);
    }
}
