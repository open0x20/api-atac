<?php

namespace App\Dto\Response;


/**
 * Class Response
 * @package App\Dto
 */
class Response
{
    /**
     * @var Meta
     */
    protected $meta;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @return Meta
     */
    public function getMeta(): Meta
    {
        return $this->meta;
    }

    /**
     * @param Meta $meta
     * @return void
     */
    public function setMeta(Meta $meta): void
    {
        $this->meta = $meta;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed|null $data
     * @return void
     */
    public function setData($data): void
    {
        $this->data = $data;
    }
}
