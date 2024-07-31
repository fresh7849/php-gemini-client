<?php

namespace Fresh\Gemini\Response\Chat;

final class ResponsePublicationDate
{
    public function __construct(
        public readonly int $year,
        public readonly int $month,
        public readonly int $day,
    ) {}

    public static function from(array $attributes): self
    {
        return new self(
            $attributes['year'],
            $attributes['month'],
            $attributes['day'],
        );
    }

    public function toArray(): array
    {
        return [
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
        ];
    }
}
