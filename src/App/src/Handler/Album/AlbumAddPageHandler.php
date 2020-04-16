<?php

declare(strict_types=1);

namespace App\Handler\Album;

use App\Form\AlbumForm;
use Domain\Album\AlbumRepository;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AlbumAddPageHandler implements RequestHandlerInterface
{
    private $albumForm;
    private $albumRepository;
    private $template;

    public function __construct(
        AlbumForm                 $albumForm,
        AlbumRepository           $albumRepository,
        TemplateRendererInterface $template
    ) {
        $this->albumForm       = $albumForm;
        $this->albumRepository = $albumRepository;
        $this->template        = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        if ($request->getAttribute('albumForm')) {
            $this->albumForm = $request->getAttribute('albumForm');
        }

        return new HtmlResponse(
            $this->template->render('app::Album/add', [
                'albumForm' => $this->albumForm,
                'uniqueError' => $request->getAttribute('uniqueError') ?? '',
            ])
        );
    }
}
