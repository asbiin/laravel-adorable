<?php

namespace LaravelAdorable\Adorable;

use Illuminate\Support\Str;

class HashCollection
{
    /**
     * Collection of values.
     *
     * @var array
     */
    private $array;

    /**
     * Hash method to use.
     *
     * @var string
     */
    private $method;

    public function __construct(array $array, string $method = 'sum')
    {
        $this->array = $array;
        $this->method = $method;
    }

    /**
     * Get a value from the collection.
     *
     * @param  string  $key
     * @return mixed
     */
    public function get(string $key)
    {
        if (($count = count($this->array)) === 0) {
            return null;
        }

        $hash = $this->hash($key);

        return $this->array[$hash % $count];
    }

    private function hash(string $key)
    {
        $split = Str::of($key)->split(1);
        if (is_callable([$split, $this->method])) {
            return call_user_func([$split, $this->method], function ($char) {
                return mb_ord($char);
            });
        }

        throw new \InvalidArgumentException("Method {$this->method} not found on \Illuminate\Support\Collection");
    }
}
