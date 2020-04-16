<?php

declare(strict_types=1);

namespace Domain\Album;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Album
 *
 * @ORM\Table(name="album", uniqueConstraints={@ORM\UniqueConstraint(columns={"artist", "title"})})
 * @ORM\Entity
 */
class Album
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid_binary")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @var UuidInterface
     */
    private $id;

    /**
     * @ORM\Column(name="artist", type="string", length=255, nullable=false)
     *
     * @var string
     */
    private $artist;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     *
     * @var string
     */
    private $title;

    /**
     * Get id
     *
     * @return UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set artist
     *
     * @param string $artist
     * @return $this
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist
     *
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
