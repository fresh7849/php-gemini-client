<?php

namespace Fresh\Gemini\Traits;

/**
 * @template TArray of array
 *
 * @mixin Response<TArray>
 */
trait TArrayAccessible
{
    /**
     * @param  int|string  $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->toArray());
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->toArray()[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): never
    {
        throw new \BadMethodCallException('Cannot set response attributes.');
    }

    public function offsetUnset(mixed $offset): never
    {
        throw new \BadMethodCallException('Cannot unset response attributes.');
    }
}
