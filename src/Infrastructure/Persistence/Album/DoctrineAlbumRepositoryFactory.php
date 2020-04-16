<?php
declare(strict_types=1);

namespace Infrastructure\Persistence\Album;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class DoctrineAlbumRepositoryFactory
{
    public function __invoke(ContainerInterface $container) : DoctrineAlbumRepository
    {
        $entityManager = $container->get(EntityManager::class);
        return new DoctrineAlbumRepository($entityManager);
    }
}
