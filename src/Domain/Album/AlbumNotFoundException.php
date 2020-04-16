<?php
declare(strict_types=1);

namespace Domain\Album;

use Domain\Exception\RecordNotFoundException;

class AlbumNotFoundException extends RecordNotFoundException
{
    public $message = 'The album you requested does not exist.';
}
