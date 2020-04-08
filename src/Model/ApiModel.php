<?php

namespace App\Model;


use App\Dto\Request\AddDto;
use App\Dto\Request\IdDto;

/**
 * Class DefaultModel
 * @package App\Model
 */
class ApiModel
{
    /**
     * @param AddDto $addDto
     * @return string
     */
    public static function add(AddDto $addDto)
    {
        // create/find artist
        // TODO

        // save ytv into database
        // TODO

        // return database id
        // TODO
    }

    /**
     * @param IdDto $idDto
     * @return string
     */
    public static function update(IdDto $idDto)
    {
        // find ytv in database
        // TODO

        // remove ytv from local storage
        // TODO

        // update ytv in database (set modified = 1)
        // TODO

        // return updated database id
        // TODO
    }

    /**
     * @param IdDto $idDto
     * @return string
     */
    public static function delete(IdDto $idDto)
    {
        // find ytv in database
        // TODO

        // remove ytv from local storage
        // TODO

        // remove ytv from database
        // TODO

        // return removed database id
        // TODO
    }
}
