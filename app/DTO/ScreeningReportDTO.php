<?php

namespace App\DTO;

class ScreeningReportDTO
{
    public function __construct(
        public readonly string $summary,
        public readonly array $strengths,
        public readonly array $weaknesses,
        public readonly array $suggestedQuestions,
        public readonly ?string $error = null
    ) {}

    public function isSuccessful(): bool
    {
        return $this->error === null;
    }

    public function toArray(): array
    {
        return [
            'summary' => $this->summary,
            'strengths' => $this->strengths,
            'weaknesses' => $this->weaknesses,
            'suggested_questions' => $this->suggestedQuestions,
        ];
    }

    public static function success(
        string $summary,
        array $strengths,
        array $weaknesses,
        array $suggestedQuestions
    ): self {
        return new self($summary, $strengths, $weaknesses, $suggestedQuestions);
    }

    public static function failure(string $error): self
    {
        return new self('', [], [], [], $error);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['summary'] ?? '',
            $data['strengths'] ?? [],
            $data['weaknesses'] ?? [],
            $data['suggested_questions'] ?? []
        );
    }
}
