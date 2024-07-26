<?php

namespace  Baishu\GeminiClient\Response\Chat;

final class ResponseSafetyRating
{
    /**
     * __construct.
     */
    public function __construct(
        public readonly ?string $category,
        public readonly ?string $probability,
        public readonly ?float $probabilityScore,
        public readonly ?string $severity,
        public readonly ?float $severityScore,
        public readonly ?bool $blocked,
    ) {}

    public static function from(array $attributes): self
    {
        return new self(
            $attributes['category'] ?? null,
            $attributes['probability'] ?? null,
            $attributes['probabilityScore'] ?? null,
            $attributes['severity'] ?? null,
            $attributes['severityScore'] ?? null,
            $attributes['blocked'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->category !== null) {
            $data['category'] = $this->category;
        }
        if ($this->probability !== null) {
            $data['probability'] = $this->probability;
        }
        if ($this->probabilityScore !== null) {
            $data['probabilityScore'] = $this->probabilityScore;
        }
        if ($this->severity !== null) {
            $data['severity'] = $this->severity;
        }
        if ($this->severityScore !== null) {
            $data['severityScore'] = $this->severityScore;
        }
        if ($this->blocked !== null) {
            $data['blocked'] = $this->blocked;
        }

        return $data;
    }
}
