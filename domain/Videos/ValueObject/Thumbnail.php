<?php

namespace Domain\Videos\ValueObject;

class Thumbnail
{
    public ?string $path;

    public function __construct(string $path = null)
    {
        $this->path = $path;
    }
}