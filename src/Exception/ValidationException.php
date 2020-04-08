<?php

namespace App\Exception;


use Throwable;

/**
 * Class ValidationException
 * @package App\Exception
 */
class ValidationException extends \Exception
{
    /**
     * @var array
     */
    protected $violations;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var Throwable|null
     */
    protected $previous;

    /**
     * ValidationException constructor.
     * @param array $violations
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($violations = [], $code = 400, Throwable $previous = null)
    {
        $vMapped = array();
        foreach ($violations as $violation) {
            $vMapped[] = 'Field \'' . $violation->getPropertyPath() . '\': ' . $violation->getmessage();
        }

        $this->violations = $vMapped;
        $this->code = $code;
        $this->previous = $previous;
        parent::__construct(implode("\n", $this->violations), $this->code, $this->previous);
    }

    /**
     * @return array
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
