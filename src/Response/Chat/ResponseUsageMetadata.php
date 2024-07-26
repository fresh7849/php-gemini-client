<?php

namespace Yj\GeminiClient\Response\Chat;

final class ResponseUsageMetadata
{
    public function __construct(
        public readonly int $promptTokenCount,
        public readonly int $candidatesTokenCount,
        public readonly int $totalTokenCount,
    ) {}

    public static function from(array $attributes): self
    {
        return new self(
            $attributes['promptTokenCount'],
            $attributes['candidatesTokenCount'],
            $attributes['totalTokenCount'],
        );
    }

    public function toArray(): array
    {
        return [
            'promptTokenCount' => $this->promptTokenCount,
            'candidatesTokenCount' => $this->candidatesTokenCount,
            'totalTokenCount' => $this->totalTokenCount,
        ];
    }
}
