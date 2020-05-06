<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrackRepository")
 */
class Track
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ytv;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $coverUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $album;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TrackArtist", mappedBy="track")
     */
    private $artists;

    /**
     * @ORM\Column(type="boolean")
     */
    private $modified;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYtv(): ?string
    {
        return $this->ytv;
    }

    public function setYtv(string $ytv): self
    {
        $this->ytv = $ytv;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCoverUrl(): ?string
    {
        return $this->coverUrl;
    }

    public function setCoverUrl(string $coverUrl): self
    {
        $this->coverUrl = $coverUrl;

        return $this;
    }

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function setAlbum(?string $album): self
    {
        $this->album = $album;

        return $this;
    }

    /**
     * @return Collection|TrackArtist[]
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(TrackArtist $artist): self
    {
        if (!$this->artists->contains($artist)) {
            $this->artists[] = $artist;
            $artist->setTrack($this);
        }

        return $this;
    }

    public function removeArtist(TrackArtist $artist): self
    {
        if ($this->artists->contains($artist)) {
            $this->artists->removeElement($artist);
            // set the owning side to null (unless already changed)
            if ($artist->getTrack() === $this) {
                $artist->setTrack(null);
            }
        }

        return $this;
    }

    public function getModified(): ?bool
    {
        return $this->modified;
    }

    public function setModified(bool $modified): self
    {
        $this->modified = $modified;

        return $this;
    }
}
