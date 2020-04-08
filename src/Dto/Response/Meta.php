<?php

namespace App\Dto\Response;

/**
 * Class Meta
 * @package App\Dto\Response
 */
class Meta
{
    /**
     * @var int
     */
    protected $code = 200;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return void
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return void
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }
}
