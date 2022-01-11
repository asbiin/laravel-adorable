<?php

namespace LaravelAdorable\Tests\Unit\Adorable;

use LaravelAdorable\Adorable\HashCollection;
use LaravelAdorable\Tests\FeatureTestCase;

class HashCollectionTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function it_can_get_a_value_from_a_hash_collection()
    {
        $collection = new HashCollection([
            '0' => '0',
            '1' => '1',
        ]);
        $this->assertEquals('0', $collection->get('0'));
        $this->assertEquals('1', $collection->get('1'));
    }

    /**
     * @test
     * @dataProvider hashSum
     */
    public function it_calculate_hash_sum(string $value, int $expected)
    {
        $collection = new HashCollection([]);
        $this->assertEquals($expected, $this->invokePrivateMethod($collection, 'hash', [$value]));
    }

    public function hashSum(): array
    {
        return [
            ["\0" , 0],
            ['0' , 48],
            ['a' , 97],
            ['b' , 98],
            ['ab' , 195],
        ];
    }
}
