<?php
declare(strict_types=1);

namespace Domain\Album;

use Domain\Exception\RecordExistsException;

class AlbumExistsException extends RecordExistsException
{
    public $message = 'The album data you want to save already exists in different record.';
}
