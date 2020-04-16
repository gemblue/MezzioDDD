<?php

declare(strict_types=1);

namespace App\Middleware\Album;

use App\Form\AlbumForm;
use Domain\Album\AlbumRepository;
use Psr\Container\ContainerInterface;

class AlbumSaveMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : AlbumSaveMiddleware
    {
        $albumForm       = $container->get('FormElementManager')
                                     ->get(AlbumForm::class);
        $albumRepository = $container->get(AlbumRepository::class);

        return new AlbumSaveMiddleware($albumForm, $albumRepository);
    }
}
