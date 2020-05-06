<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class IdDto
 * @package App\Dto\Request
 */
class IdDto
{
    /**
     * @Assert\NotNull()
     *
     * @var int
     */
    public $trackId;
}
