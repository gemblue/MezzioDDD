<?php

declare(strict_types=1);

namespace App\Middleware\Album;

use Domain\Album\AlbumRepository;
use Domain\Exception\RecordNotFoundException;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AlbumDeleteMiddleware implements MiddlewareInterface
{
    private $albumRepository;
    private $template;

    public function __construct(AlbumRepository $albumRepository, TemplateRendererInterface $template)
    {
        $this->albumRepository = $albumRepository;
        $this->template        = $template;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $id = $request->getAttribute('id') ?? '';

        try {
            $this->albumRepository->deleteOfId($id);
        } catch (RecordNotFoundException $e) {
            return new HtmlResponse($this->template->render('error::404', ['message' => $e->getMessage()]), 404);
        }

        return new RedirectResponse('/album');
    }
}
