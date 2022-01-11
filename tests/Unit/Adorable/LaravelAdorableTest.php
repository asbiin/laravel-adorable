<?php

namespace LaravelAdorable\Tests\Unit\Adorable;

use LaravelAdorable\Adorable\LaravelAdorable;
use LaravelAdorable\Tests\FeatureTestCase;

class LaravelAdorableTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function it_generate_square_avatar()
    {
        $adorable = new LaravelAdorable($config = $this->app['config'], $cache = $this->app['cache.store']);

        $image = $adorable->get(200, '767c9d26-5484-438d-a614-858f0b420fcc');
        $avatar = file_get_contents(__DIR__.'/../../stubs/avatar1.png');
        $base64 = 'data:image/png;base64,'.base64_encode($avatar);

        $this->assertEquals($base64, $image);
        $this->assertTrue($cache->has('adorable.042d0af447682411dd93493f94cf1b7a9aa1ceb26a8c7b75d2a88ba07dbcc89d'));
        $this->assertEquals($base64, $cache->get('adorable.042d0af447682411dd93493f94cf1b7a9aa1ceb26a8c7b75d2a88ba07dbcc89d'));
    }

    /**
     * @test
     */
    public function it_generate_circle_avatar()
    {
        config(['adorable.shape' => 'circle']);

        $adorable = new LaravelAdorable($config = $this->app['config'], $cache = $this->app['cache.store']);

        $image = $adorable->get(200, '767c9d26-5484-438d-a614-858f0b420fcc');
        $avatar = file_get_contents(__DIR__.'/../../stubs/avatar2.png');
        $base64 = 'data:image/png;base64,'.base64_encode($avatar);

        $this->assertEquals($base64, $image);
        $this->assertTrue($cache->has('adorable.b6cb42a351d2b3786df2efefaca062d032a67d7e1da38260102e1bdd35965e97'));
        $this->assertEquals($base64, $cache->get('adorable.b6cb42a351d2b3786df2efefaca062d032a67d7e1da38260102e1bdd35965e97'));
    }
}
