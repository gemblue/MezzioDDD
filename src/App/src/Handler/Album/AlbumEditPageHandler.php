<?php

declare(strict_types=1);

namespace App\Handler\Album;

use App\Form\AlbumForm;
use Domain\Album\AlbumRepository;
use Domain\Exception\RecordNotFoundException;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AlbumEditPageHandler implements RequestHandlerInterface
{
    private $albumForm;
    private $albumRepository;
    private $template;

    public function __construct(
        AlbumForm $albumForm,
        AlbumRepository $albumRepository,
        TemplateRendererInterface $template
    ) {
        $this->albumForm       = $albumForm;
        $this->albumRepository = $albumRepository;
        $this->template        = $template;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');
        try {
            $album = $this->albumRepository->findAlbumOfId($id);
            $this->albumForm->get('artist')->setValue($album->getArtist());
            $this->albumForm->get('title')->setValue($album->getTitle());
        } catch (RecordNotFoundException $e) {
            return new HtmlResponse($this->template->render('error::404', ['message' => $e->getMessage()]), 404);
        }

        if ($request->getAttribute('albumForm')) {
            $this->albumForm = $request->getAttribute('albumForm');
        }

        return new HtmlResponse(
            $this->template->render('app::Album/edit', [
                'id'          => $id,
                'albumForm'   => $this->albumForm,
                'uniqueError' => $request->getAttribute('uniqueError') ?? '',
            ])
        );
    }
}
