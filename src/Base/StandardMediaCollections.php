<?php

namespace Lunar\Base;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class StandardMediaCollections
{
    public function apply(HasMedia $model)
    {
        $fallbackUrl = config('lunar.media.fallback.url');
        $fallbackPath = config('lunar.media.fallback.path');

        $collection = $model->addMediaCollection('images');

        if ($fallbackUrl) {
            $collection = $collection->useFallbackUrl($fallbackUrl);
        }

        if ($fallbackPath) {
            $collection = $collection->useFallbackPath($fallbackPath);
        }

        $this->registerConversions($collection);
    }

    protected function registerConversions(MediaCollection $collection): void
    {
        $conversions = [
            'zoom' => [
                'width' => 500,
                'height' => 500,
            ],
            'large' => [
                'width' => 800,
                'height' => 800,
            ],
            'medium' => [
                'width' => 500,
                'height' => 500,
            ],
        ];

        $collection->registerMediaConversions(function (Media $media) use ($conversions) {
            foreach ($conversions as $key => $conversion) {
                $this->addMediaConversion($key)
                    ->fit(
                        Manipulations::FIT_FILL,
                        $conversion['width'],
                        $conversion['height']
                    )->keepOriginalImageFormat();
            }
        });
    }
}
