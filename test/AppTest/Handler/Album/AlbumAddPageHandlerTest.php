<?php

declare(strict_types=1);

namespace AppTest\Handler\Album;

use AppTest\DatabaseTestCase;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;

class AlbumAddPageHandlerTest extends DatabaseTestCase
{
    public function testOpenAlbumPageAndSubmitValidData()
    {
        $uri           = new Uri('/album/add');
        $serverRequest = new ServerRequest([], [], $uri);
        $serverRequest = $serverRequest->withMethod('POST');
        $serverRequest = $serverRequest->withParsedBody(
            [
                'artist' => 'Sheila On 7',
                'title'  => 'Melompat Lebih Tinggi',
            ]
        );
        
        $response = $this->app->handle($serverRequest);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
