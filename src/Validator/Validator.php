<?php

namespace App\Validator;


use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class Validator
 * @package App\Validator
 */
class Validator
{
    /**
     * @var ValidatorInterface|null
     */
    private static $validator = null;

    /**
     * @param ValidatorInterface $validator
     */
    public static function initialize(ValidatorInterface $validator)
    {
        self::$validator = $validator;
    }

    /**
     * @return ValidatorInterface|null
     */
    public static function getInstance()
    {
        return self::$validator;
    }
}
