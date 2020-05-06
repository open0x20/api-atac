<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UpdateDto
 * @package App\Dto\Request
 */
class UpdateDto
{
    /**
     * @Assert\NotNull()
     *
     * @var int
     */
    public $trackId;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Url()
     *
     * @var string
     */
    public $urlYtv;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1", max="100")
     *
     * @var string
     */
    public $title;

    /**
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\NotNull(),
     *     @Assert\Length(min="1", max="100")
     * })
     * @Assert\NotNull()
     * @Assert\Count(min=1, max=10)
     *
     * @var string[]
     */
    public $artists;

    /**
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\NotNull(),
     *     @Assert\Length(min="1", max="100")
     * })
     * @Assert\Count(max=10)
     *
     * @var string[]
     */
    public $featuring;

    /**
     * @Assert\Length(min="1", max="100")
     *
     * @var string
     */
    public $album;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Url()
     *
     * @var string
     */
    public $urlCover;
}
