<?php

declare(strict_types=1);

namespace App\Handler\Album;

use Domain\Album\AlbumRepository;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AlbumPageHandler implements RequestHandlerInterface
{
    private $albumRepository;
    private $template;

    public function __construct(AlbumRepository $albumRepository, TemplateRendererInterface $template)
    {
        $this->albumRepository = $albumRepository;
        $this->template        = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse(
            $this->template->render('app::Album/index', [
                'albums' => $this->albumRepository->findAll(),
            ])
        );
    }
}
