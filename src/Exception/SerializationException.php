<?php

namespace App\Exception;


use Throwable;

/**
 * Class SerializationException
 * @package App\Exception
 */
class SerializationException extends \Exception
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var Throwable|null
     */
    protected $previous;

    /**
     * SerializationException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 400, Throwable $previous = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->previous = $previous;
        parent::__construct($this->message, $this->code, $this->previous);
    }
}
