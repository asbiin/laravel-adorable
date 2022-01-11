<?php

namespace LaravelAdorable\Adorable;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use Intervention\Image\Image;
use Intervention\Image\Facades\Image as ImageFacade;
use Intervention\Image\AbstractShape;

class LaravelAdorable
{
    const DEFAULT_SIZE = 400;

    private $colors;
    private $eyes;
    private $noses;
    private $mouths;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Cache
     */
    private $cache;

    public function __construct(Config $config, Cache $cache)
    {
        $this->config = $config;
        $this->cache = $cache;
        $this->colors = new HashCollection($config->get('adorable.colors'));
        $this->eyes = new HashCollection(static::files('eyes'));
        $this->noses = new HashCollection(static::files('noses'));
        $this->mouths = new HashCollection(static::files('mouths'));
    }

    protected static function files(string $type)
    {
        return array_slice(scandir(__DIR__."/../../resources/img/$type"), 2);
    }

    public function get(string $size, string $uuid)
    {
        $values = $this->hash($uuid);

        $key = $this->cacheKey($size, $uuid, $values);

        return $this->cache->get($key, function () use ($key, $values) {
            $image = $this->buildAvatar($values);

            $base64 = (string) $image->encode('data-url');

            $this->cache->forever($key, $base64);

            return $base64;
        });
    }

    private function cacheKey(string $size, string $uuid, array $values): string
    {
        $values['size'] = $size;
        $values['uuid'] = $uuid;
        $values['shape'] = $this->config->get('adorable.shape');
        $values['border.size'] = $this->config->get('adorable.border.size');
        $values['border.color'] = $this->config->get('adorable.border.color');

        return 'adorable.'.hash('sha256', implode('-', $values));
    }

    private function hash(string $uuid): array
    {
        return [
            'background' => $this->colors->get($uuid),
            'eyes' => $this->eyes->get($uuid),
            'noses' => $this->noses->get($uuid),
            'mouths' => $this->mouths->get($uuid),
        ];
    }

    private function buildAvatar(array $values)
    {
        $width = $this->config->get('adorable.width');
        $height = $this->config->get('adorable.height');

        /** @var \Intervention\Image\ImageManager $manager */
        $manager = ImageFacade::configure([
            'driver' => $this->config->get('adorable.driver')
        ]);
        $image = $manager->canvas(self::DEFAULT_SIZE, self::DEFAULT_SIZE);

        $this->createShape($image, $values);
        $this->insert($image, $values, 'eyes');
        $this->insert($image, $values, 'noses');
        $this->insert($image, $values, 'mouths');
        $image->resize($width, $height);

        return $image;
    }

    private function createShape(Image $image, array $values)
    {
        $shape = $this->config->get('adorable.shape');
        switch ($shape)
        {
            case 'circle':
                return $this->createCircleShape($image, $values);

            case 'square':
                return $this->createSquareShape($image, $values);

            default:
                throw new \InvalidArgumentException("Shape [$shape] currently not supported.");
        }
    }

    private function createCircleShape(Image $image, array $values)
    {
        $circleDiameter = self::DEFAULT_SIZE - $this->config->get('adorable.border.size');
        $x = $y = self::DEFAULT_SIZE / 2;

        $image->circle(
            $circleDiameter,
            $x,
            $y,
            function (AbstractShape $draw) use ($values) {
                $draw->background($values['background']);
                $draw->border($this->config->get('adorable.border.size'), $this->getBorderColor($values));
            }
        );
    }

    private function createSquareShape(Image $image, array $values)
    {
        $edge = (int) ceil($this->config->get('adorable.border.size') / 2);
        $x = $y = $edge;
        $width = $height = self::DEFAULT_SIZE - $edge;

        $image->rectangle(
            $x,
            $y,
            $width,
            $height,
            function (AbstractShape $draw) use ($values) {
                $draw->background($values['background']);
                $draw->border($this->config->get('adorable.border.size'), $this->getBorderColor($values));
            }
        );
    }

    private function getBorderColor(array $values)
    {
        switch ($color = $this->config->get('adorable.border.color'))
        {
            case 'white':
                return '#fff';

            case 'background':
                return $values['background'];

            default:
                return $color;
        }
    }

    private function insert(Image $image, array $values, string $type)
    {
        $filename = $values[$type];

        $image->insert(
            __DIR__."/../../resources/img/$type/$filename",
            'center'
        );
    }
}
