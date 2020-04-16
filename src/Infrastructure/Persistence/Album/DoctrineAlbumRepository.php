<?php
declare(strict_types=1);

namespace Infrastructure\Persistence\Album;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Domain\Album\Album;
use Domain\Album\AlbumExistsException;
use Domain\Album\AlbumNotFoundException;
use Domain\Album\AlbumRepository;

class DoctrineAlbumRepository implements AlbumRepository
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll(): array
    {
        return $this->entityManager
            ->getRepository(Album::class)
            ->findAll();
    }

    public function findAlbumOfId(string $id): Album
    {
        $album = $this->entityManager
                ->getRepository(Album::class)
                ->find($id);

        if (! $album instanceof Album) {
            throw new AlbumNotFoundException();
        }

        return $album;
    }

    public function save(array $data): void
    {
        $album = $data['id']
            ? $this->findAlbumOfId($data['id'])
            : new Album();

        $album->setArtist($data['artist']);
        $album->setTitle($data['title']);

        try {
            $this->entityManager->persist($album);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new AlbumExistsException();
        }
    }

    public function deleteOfId(string $id): void
    {
        $album = $this->findAlbumOfId($id);

        $this->entityManager->remove($album);
        $this->entityManager->flush();
    }
}
