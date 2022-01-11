<?php

namespace LaravelAdorable\Adorable;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use Intervention\Image\AbstractShape;
use Intervention\Image\Facades\Image as ImageFacade;
use Intervention\Image\Image;

class LaravelAdorable
{
    /**
     * Default avatar size.
     *
     * @var int
     */
    public const DEFAULT_SIZE = 400;

    /**
     * @var HashCollection
     */
    private $colors;

    /**
     * @var HashCollection
     */
    private $eyes;

    /**
     * @var HashCollection
     */
    private $noses;

    /**
     * @var HashCollection
     */
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

    protected static function files(string $type): array
    {
        return array_slice(scandir(__DIR__."/../../resources/img/$type"), 2);
    }

    /**
     * Get base64 data-url of avatar.
     *
     * @param  string  $size
     * @param  string  $uuid
     * @return string
     */
    public function get(string $size, string $uuid): string
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

    private function cacheKey(string $size, string $uuid, array &$values): string
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

    private function buildAvatar(array $values): Image
    {
        /** @var \Intervention\Image\ImageManager $manager */
        $manager = ImageFacade::configure([
            'driver' => $this->config->get('adorable.driver'),
        ]);
        $image = $manager->canvas(self::DEFAULT_SIZE, self::DEFAULT_SIZE);

        $this->createShape($image, $values);
        $this->insert($image, $values, 'eyes');
        $this->insert($image, $values, 'noses');
        $this->insert($image, $values, 'mouths');
        $image->resize($values['size'], $values['size']);

        return $image;
    }

    private function createShape(Image $image, array $values): void
    {
        $shape = $this->config->get('adorable.shape');
        switch ($shape) {
            case 'circle':
                $this->createCircleShape($image, $values);
                break;

            case 'square':
                $this->createSquareShape($image, $values);
                break;

            default:
                throw new \InvalidArgumentException("Shape [$shape] currently not supported.");
        }
    }

    private function createCircleShape(Image $image, array $values): void
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

    private function createSquareShape(Image $image, array $values): void
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

    private function getBorderColor(array $values): string
    {
        switch ($color = $this->config->get('adorable.border.color')) {
            case 'white':
                return '#fff';

            case 'background':
                return $values['background'];

            default:
                return $color;
        }
    }

    private function insert(Image $image, array $values, string $type): void
    {
        $filename = $values[$type];

        $image->insert(
            __DIR__."/../../resources/img/$type/$filename",
            'center'
        );
    }
}
