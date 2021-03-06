<?php

declare(strict_types=1);

namespace App;

use Domain;
use Infrastructure;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables'         => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'          => [
                Handler\HomePageHandler::class => Handler\HomePageHandlerFactory::class,
                Domain\Album\AlbumRepository::class
                    => Infrastructure\Persistence\Album\DoctrineAlbumRepositoryFactory::class,
                Handler\Album\AlbumPageHandler::class         => ReflectionBasedAbstractFactory::class,
                Middleware\Album\AlbumSaveMiddleware::class   => Middleware\Album\AlbumSaveMiddlewareFactory::class,
                Middleware\Album\AlbumDeleteMiddleware::class => ReflectionBasedAbstractFactory::class,
            ],
            'abstract_factories' => [
                Handler\Album\AlbumPageHandlerRequireFormFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => [__DIR__ . '/../templates/app'],
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }
}
