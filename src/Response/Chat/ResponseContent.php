<?php

namespace Baishu\GeminiClient\Response\Chat;

final class ResponseContent
{
    /**
     * __construct.
     *
     * @param  null|array<int, ResponsePart>  $parts
     */
    public function __construct(
        public readonly ?string $role,
        public readonly ?array $parts,
    ) {}

    public static function from(array $attributes): self
    {
        return new self(
            $attributes['role'] ?? null,
            isset($attributes['parts']) ? array_map(fn (array $result): ResponsePart => ResponsePart::from($result), $attributes['parts']) : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->role !== null) {
            $data['role'] = $this->role;
        }
        if ($this->parts !== null) {
            $data['parts'] = array_map(fn (ResponsePart $item): array => $item->toArray(), $this->parts);
        }

        return $data;
    }
}
