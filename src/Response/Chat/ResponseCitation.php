<?php

namespace  Baishu\GeminiClient\Response\Chat;

final class ResponseCitation
{
    public function __construct(
        public readonly ?int $startIndex,
        public readonly ?int $endIndex,
        public readonly ?string $uri,
        public readonly ?string $title,
        public readonly ?string $license,
        public readonly ?ResponsePublicationDate $publicationDate,
    ) {}

    public static function from(array $attributes): self
    {
        return new self(
            $attributes['startIndex'] ?? null,
            $attributes['endIndex'] ?? null,
            $attributes['uri'] ?? null,
            $attributes['title'] ?? null,
            $attributes['license'] ?? null,
            isset($attributes['publicationDate']) ? ResponsePublicationDate::from($attributes['publicationDate']) : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->startIndex !== null) {
            $data['startIndex'] = $this->startIndex;
        }
        if ($this->endIndex !== null) {
            $data['endIndex'] = $this->endIndex;
        }
        if ($this->uri !== null) {
            $data['uri'] = $this->uri;
        }
        if ($this->title !== null) {
            $data['title'] = $this->title;
        }
        if ($this->license !== null) {
            $data['license'] = $this->license;
        }
        if ($this->publicationDate !== null) {
            $data['publicationDate'] = $this->publicationDate->toArray();
        }

        return $data;
    }
}
