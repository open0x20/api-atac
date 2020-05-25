<?php

namespace App\Helper;


class ParserHelper
{
    /**
     * @param string $source
     * @param string $tokenA
     * @param string $tokenB
     * @param int $offset
     * @return mixed|string|null
     */
    public static function getStringBetween(string $source, string $tokenA, string $tokenB, int $offset = 0, int $cutOffStart = 0, int $cutOffFromEnd = 0)
    {
        $source = explode($tokenA, $source);
        if (count($source) <= ($offset + 1)) {
            return null;
        }
        $source = explode($tokenB, $source[1 + $offset]);
        if (count($source) <= 1) {
            return null;
        }

        $source[0] = mb_substr($source[0], $cutOffStart, (mb_strlen($source[0]) - $cutOffFromEnd));

        return $source[0];
    }
}
