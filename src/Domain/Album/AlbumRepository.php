<?php
declare(strict_types=1);

namespace Domain\Album;

interface AlbumRepository
{
    public function findAll(): array;
    public function findAlbumOfId(string $id): Album;
    public function save(array $data): void;
    public function deleteOfId(string $id): void;
}
