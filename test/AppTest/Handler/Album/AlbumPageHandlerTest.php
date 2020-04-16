<?php

declare(strict_types=1);

namespace AppTest\Handler\Album;

use AppTest\DatabaseTestCase;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;

class AlbumPageHandlerTest extends DatabaseTestCase
{
    public function testOpenAlbumIndexHasNoData()
    {
        $uri           = new Uri('/album');
        $serverRequest = new ServerRequest([], [], $uri);

        $response = $this->app->handle($serverRequest);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('No album found.', (string) $response->getBody());
    }
}
