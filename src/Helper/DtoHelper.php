<?php

namespace App\Helper;


use App\Dto\Response\Meta;
use App\Dto\Response\Response;

/**
 * Class DtoHelper
 * @package App\Helper
 */
class DtoHelper
{
    /**
     * @param $code
     * @param null $data
     * @param array $errors
     * @return Response
     */
    public static function createResponseDto($code, $data = null, $errors = [])
    {
        $response = new Response();
        $meta = new Meta();

        $meta->setCode($code);
        $meta->setErrors($errors);

        $response->setMeta($meta);
        $response->setData($data);

        return $response;
    }
}
