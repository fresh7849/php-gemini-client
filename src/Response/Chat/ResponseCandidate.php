<?php

namespace Fresh\Gemini\Response\Chat;

final class ResponseCandidate
{
    /**
     * __construct.
     *
     * @param  null|array<int, ResponseSafetyRating>  $safetyRatings
     */
    public function __construct(
        public readonly ?ResponseContent $content,
        public readonly ?string $finishReason,
        public readonly ?array $safetyRatings,
        public readonly ?ResponseCitationMetadata $citationMetadata,
    ) {}

    public static function from(array $attributes): self
    {
        return new self(
            isset($attributes['content']) ? ResponseContent::from($attributes['content']) : null,
            $attributes['finishReason'] ?? null,
            isset($attributes['safetyRatings']) ? array_map(fn (array $result): ResponseSafetyRating => ResponseSafetyRating::from(
                $result
            ), $attributes['safetyRatings']) : null,
            isset($attributes['citationMetadata']) ? ResponseCitationMetadata::from($attributes['citationMetadata']) : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->content !== null) {
            $data['content'] = $this->content->toArray();
        }
        if ($this->finishReason !== null) {
            $data['finishReason'] = $this->finishReason;
        }
        if ($this->safetyRatings !== null) {
            $data['safetyRatings'] = array_map(fn (ResponseSafetyRating $result): array => $result->toArray(), $this->safetyRatings);
        }
        if ($this->citationMetadata !== null) {
            $data['citationMetadata'] = $this->citationMetadata->toArray();
        }

        return $data;
    }
}
