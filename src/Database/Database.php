<?php

namespace App\Database;


use Doctrine\ORM\EntityManagerInterface;

class Database
{
    private static $entityManager = null;

    public static function initialize(EntityManagerInterface $entityManager)
    {
        self::$entityManager = $entityManager;
    }

    /**
     * @return EntityManagerInterface|null
     */
    public static function getInstance()
    {
        return self::$entityManager;
    }
}
