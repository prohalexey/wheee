<?php

namespace Domain\Videos\Repository;

use Domain\Videos\Entity\Video;

interface VideoRepositoryContract
{
    public const ORDER_BY_VIEWS = 'views';
    public const ORDER_BY_DATE = 'date';

    public const ORDER_DIRECTION_ASC = 'ASC';
    public const ORDER_DIRECTION_DESC = 'DESC';

    public function getTotal(): int;

    public function find(int $id): ?Video;

    public function incrementViewCounter(Video $video);
}