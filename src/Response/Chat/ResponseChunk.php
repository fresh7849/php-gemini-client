<?php

namespace Yj\GeminiClient\Response\Chat;

use Yj\GeminiClient\Contracts\ResponseContract;
use Yj\GeminiClient\Traits\TArrayAccessible;

/**
 * ResponseChunk.
 */
final class ResponseChunk implements ResponseContract
{
    use TArrayAccessible;

    /**
     * Undocumented function.
     *
     * @param  array<int, ResponseCandidate>  $candidates
     */
    public function __construct(
        public readonly array $candidates,
        public readonly ?ResponseUsageMetadata $usageMetadata,
    ) {}

    public static function from(array $attributes): self
    {
        return new self(
            array_map(fn (array $attribute): ResponseCandidate => ResponseCandidate::from($attribute), $attributes['candidates']),
            isset($attributes['usageMetadata']) ? ResponseUsageMetadata::from($attributes['usageMetadata']) : null,
        );
    }

    public function toArray(): array
    {
        $data = [
            'candidates' => array_map(fn (ResponseCandidate $result): array => $result->toArray(), $this->candidates),
        ];
        if (! empty($this->usageMetadata)) {
            $data['usageMetadata'] = $this->usageMetadata->toArray();
        }

        return $data;
    }
}
