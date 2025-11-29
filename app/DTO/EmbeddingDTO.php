<?php

namespace App\DTO;

class EmbeddingDTO
{
    public function __construct(
        public readonly array $vector,
        public readonly ?string $error = null
    ) {}

    public function isSuccessful(): bool
    {
        return $this->error === null && ! empty($this->vector);
    }

    public static function success(array $vector): self
    {
        return new self($vector);
    }

    public static function failure(string $error): self
    {
        return new self([], $error);
    }

    /**
     * Get the vector as a string representation for storage.
     */
    public function toStorageFormat(): string
    {
        return '['.implode(',', $this->vector).']';
    }
}
