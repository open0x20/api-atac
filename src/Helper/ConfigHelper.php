<?php

namespace App\Helper;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class ConfigHelper
 * @package App\Helper
 */
class ConfigHelper
{
    /**
     * @var ParameterBagInterface|null
     */
    private static $pb = null;

    /**
     * @param ParameterBagInterface $parameterBag
     */
    public static function initialize(ParameterBagInterface $parameterBag)
    {
        self::$pb = $parameterBag;
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        return self::$pb->get($name);
    }
}
