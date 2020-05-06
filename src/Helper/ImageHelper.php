<?php

namespace App\Helper;


class ImageHelper
{
    /**
     * @param $rawImage
     * @return string|null
     */
    public static function getFiletype($rawImage)
    {
        if ($rawImage[1] === 'P' && $rawImage[2] === 'N' && $rawImage[3] === 'G') {
            return 'png';
        } else if (ord($rawImage[0]) === 255 && ord($rawImage[1]) === 216) {
            return 'jpg';
        }

        return null;
    }
}
