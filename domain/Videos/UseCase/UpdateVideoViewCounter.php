<?php

namespace Domain\Videos\UseCase;

use Domain\Videos\Entity\Video;
use Domain\Videos\Repository\VideoRepositoryContract;

class UpdateVideoViewCounter
{
    private VideoRepositoryContract $videoRepository;

    public function __construct(VideoRepositoryContract $videoRepository)
    {
        $this->videoRepository = $videoRepository;
     }

    public function execute(Video $video)
    {
        try {
            // Todo: Broken domain logic. Need to think about it
            $this->videoRepository->incrementViewCounter($video);
        } catch (\Exception $e) {
            // Todo: Log here
        }
    }
}