<?php

namespace Yj\GeminiClient\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Yj\GeminiClient\Response\Chat\ResponseChunk;

/**
 * StreamResponse.
 */
class StreamResponse implements \IteratorAggregate
{
    public function __construct(
        private readonly ResponseInterface $response,
    ) {}

    public function getIterator(): \Generator
    {
        $stream = $this->response->getBody();
        while (! $stream->eof()) {
            $item = $this->readItem($stream);
            if (empty($item)) {
                break;
            }
            yield ResponseChunk::from($item);
        }
    }

    /**
     * Read a item from the stream.
     */
    private function readItem(StreamInterface $stream): array
    {
        $buffer = '';
        $flag = true;

        while (! $stream->eof()) {
            $byte = $stream->read(1);
            $buffer .= $byte;
            if (strpos($buffer, '{') !== false && $flag) {
                $buffer = substr($buffer, strpos($buffer, '{'));
                $flag = false;

                continue;
            }
            try {
                return json5_decode($buffer, true);
            } catch (\Throwable $th) {
                continue;
            }
        }

        return [];
    }
}
