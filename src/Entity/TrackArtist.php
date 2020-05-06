<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrackArtistRepository")
 */
class TrackArtist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist", inversedBy="tracks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $artist;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Track", inversedBy="artists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $track;

    /**
     * @ORM\Column(type="boolean")
     */
    private $featuring;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getTrack(): ?Track
    {
        return $this->track;
    }

    public function setTrack(?Track $track): self
    {
        $this->track = $track;

        return $this;
    }

    public function getFeaturing(): ?bool
    {
        return $this->featuring;
    }

    public function setFeaturing(bool $featuring): self
    {
        $this->featuring = $featuring;

        return $this;
    }
}
