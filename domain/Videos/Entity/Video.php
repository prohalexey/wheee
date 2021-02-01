<?php

namespace Domain\Videos\Entity;

use Carbon\Carbon;

class Video
{
    public int $id;
    public string $title;
    public int $duration;
    public Carbon $createdAt;
    public int $counter;

    public function __construct(
        int $id,
        string $title,
        int $duration,
        Carbon $createdAt,
        int $counter = 0
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->duration = $duration;
        $this->createdAt = $createdAt;
        $this->counter = $counter;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['title'],
            $data['duration'],
            Carbon::create($data['created_at']),
            $data['counter']
        );
    }
}