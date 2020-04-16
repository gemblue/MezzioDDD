<?php

declare(strict_types=1);

namespace App\Middleware\Album;

use App\Form\AlbumForm;
use Domain\Album\AlbumRepository;
use Domain\Exception\RecordExistsException;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AlbumSaveMiddleware implements MiddlewareInterface
{
    private $albumForm;
    private $albumRepository;

    public function __construct(AlbumForm $albumForm, AlbumRepository $albumRepository)
    {
        $this->albumForm       = $albumForm;
        $this->albumRepository = $albumRepository;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if ($request->getMethod() !== 'POST') {
            return $handler->handle($request);
        }

        $data = $request->getParsedBody();
        $this->albumForm->setData($data);

        if ($this->albumForm->isValid()) {
            $data       = $this->albumForm->getData();
            $data['id'] = $request->getAttribute('id') ?? '';

            try {
                $this->albumRepository->save($data);
            } catch (RecordExistsException $e) {
                $request = $request->withAttribute('albumForm', $this->albumForm);
                $request = $request->withAttribute('uniqueError', $e->getMessage());
                return $handler->handle($request);
            }

            return new RedirectResponse('/album');
        }

        $request = $request->withAttribute('albumForm', $this->albumForm);
        return $handler->handle($request);
    }
}
