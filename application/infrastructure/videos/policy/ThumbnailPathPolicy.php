<?php

namespace application\infrastructure\videos\policy;

use Domain\Videos\Entity\Video;
use Domain\Videos\ValueObject\Thumbnail;

class ThumbnailPathPolicy
{
    public function getThumbnail(Video $video): Thumbnail
    {
        $path = sprintf('/img/img-0%s.jpg', strrev((string)$video->id)[0]);

        return new Thumbnail($path);
    }

    /**
     * @param Video[] $videos
     * @return
     */
    public function getThumbnails(array $videos): array
    {
        $thumbnails = [];

        foreach ($videos as $video) {
            $thumbnails[$video->id] = $this->getThumbnail($video);
        }

        return $thumbnails;
    }
}