<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DifferenceDto
 * @package App\Dto\Request
 */
class DifferenceDto
{
    /**
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\NotNull(),
     *     @Assert\Length(min="36", max="36")
     * })
     * @Assert\NotNull()
     *
     * @var string[]
     */
    public $filenames;
}
