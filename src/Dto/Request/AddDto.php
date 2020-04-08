<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddDto
 * @package App\Dto\Request
 */
class AddDto
{
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
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1", max="100")
     *
     * @var string
     */
    public $artist;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="1", max="100")
     *
     * @var string
     */
    public $featuring;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
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
