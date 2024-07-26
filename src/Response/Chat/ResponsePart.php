<?php

namespace  Baishu\GeminiClient\Response\Chat;

final class ResponsePart
{
    public function __construct(
        public readonly string $text,
    ) {}

    public static function from(array $attributes): self
    {
        return new self(
            $attributes['text'],
        );
    }

    public function toArray(): array
    {
        return [
            'text' => $this->text,
        ];
    }
}
