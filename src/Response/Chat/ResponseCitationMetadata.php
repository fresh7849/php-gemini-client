<?php

namespace Fresh\Gemini\Response\Chat;

final class ResponseCitationMetadata
{
    /**
     * __construct.
     *
     * @param  null|array<int, ResponseCitation>  $citations
     */
    public function __construct(
        public readonly ?array $citations,
    ) {}

    public static function from(array $attributes): self
    {
        return new self(
            array_map(fn (array $result): ResponseCitation => ResponseCitation::from(
                $result
            ), $attributes['citations'] ?? []),
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->citations !== null) {
            $data['citations'] = array_map(fn (ResponseCitation $result): array => $result->toArray(), $this->citations);
        }

        return $data;
    }
}
