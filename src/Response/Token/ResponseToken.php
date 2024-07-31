<?php

namespace Fresh\Gemini\Response\Token;

final class ResponseToken
{
    public function __construct(
        public readonly int $totalTokens,
        public readonly int $totalBillableCharacters,
    ) {}

    /**
     * @param  array<string,int>  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['totalTokens'],
            $attributes['totalBillableCharacters']
        );
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        $data = [
            'totalTokens' => $this->totalTokens,
            'totalBillableCharacters' => $this->totalBillableCharacters,
        ];

        return $data;
    }
}
