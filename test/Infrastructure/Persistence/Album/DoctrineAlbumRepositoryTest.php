<?php

declare(strict_types=1);

namespace InfrastructureTest\Persistence\Album;

use Domain\Album\Album;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Infrastructure\Persistence\Album\DoctrineAlbumRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DoctrineAlbumRepositoryTest extends TestCase
{
    protected $entityManagerSpy;
    protected $doctrineAlbumRepository;

    protected function setUp(): void
    {
        $this->entityManagerSpy       = $this->prophesize(EntityManager::class);
        $this->doctrineAlbumRepository = new DoctrineAlbumRepository(
            $this->entityManagerSpy->reveal()
        );
    }

    public function testFindAll()
    {
        $expected         =  [
            0 => (function ($album) {
               $album->id     = Uuid::uuid4();
               $album->artist = 'Siti Nurhaliza';
               $album->title  = 'Purnama Merindu';

               return $album;
            })->bindTo($album = new Album(), Album::class)($album),
        ];

        $objectRepositoryFake = new class($this->entityManagerSpy->reveal(), $this->prophesize(ClassMetadata::class)->reveal(), $expected) extends EntityRepository {

                private $expected;
                
                public function __construct(EntityManagerInterface $em, ClassMetadata $cm, array $expected)
                {
                    $this->expected = $expected;
                }

                public function findAll()
                {
                    return $this->expected;
                }
        };

        $this->entityManagerSpy
             ->getRepository(Album::class)
             ->willReturn($objectRepositoryFake)
             ->shouldBeCalled();

        $this->assertEquals($expected, $this->doctrineAlbumRepository->findAll());
        $this->entityManagerSpy->getRepository(Album::class)->shouldHaveBeenCalled();
    }
}