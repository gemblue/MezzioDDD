<?php

declare(strict_types=1);

namespace App\Handler\Album;

use App\Form\AlbumForm;
use Domain\Album\AlbumRepository;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Mezzio\Template\TemplateRendererInterface;
use Interop\Container\ContainerInterface;

class AlbumPageHandlerRequireFormFactory implements AbstractFactoryInterface
{
    private const PAGES = [
        AlbumAddPageHandler::class,
        AlbumEditPageHandler::class,
    ];

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return in_array($requestedName, self::PAGES);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $albumForm       = $container->get('FormElementManager')
                                     ->get(AlbumForm::class);
        $albumRepository = $container->get(AlbumRepository::class);
        $template        = $container->get(TemplateRendererInterface::class);

        return new $requestedName($albumForm, $albumRepository, $template);
    }
}
