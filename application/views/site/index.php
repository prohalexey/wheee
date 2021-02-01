<?php

use common\helpers\objectViews\DurationView;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $videos \Domain\Videos\Entity\Video[] */
/* @var $thumbnails \Domain\Videos\ValueObject\Thumbnail[] */
/* @var $total int */
/* @var $page int */
/* @var $pages int */
/* @var $orderDirection string */
/* @var $orderBy string */

?>

<div class="container-fluid tm-container-content tm-mt-60">
    <div class="row mb-4">
        <h1 class="col-6 tm-text-primary">
            Video List: <br>
            Let's view our <?= number_format($total) ?> videos
        </h1>
    </div>

    <div class="row">
        <div class="col-md-3">
            Sort type:
            <a href="<?= Url::to(['site/index', 'orderDirection' => $orderDirection, 'page' => $page, 'orderBy' => 'views']) ?>">Views</a>
            |
            <a href="<?= Url::to(['site/index', 'orderDirection' => $orderDirection, 'page' => $page, 'orderBy' => 'date']) ?>">Date</a>
        </div>

        <div class="col-md-3">
            Sort direction:
            <a href="<?= Url::to(['site/index', 'orderDirection' => 'asc', 'page' => $page, 'orderBy' => $orderBy]) ?>">Asc</a>
            |
            <a href="<?= Url::to(['site/index', 'orderDirection' => 'desc', 'page' => $page, 'orderBy' => $orderBy]) ?>">Desc</a>
        </div>
    </div>
</div>

<div class="container-fluid tm-container-content tm-mt-60">

    <div class="row tm-mb-90 tm-gallery">
        <?php foreach($videos as $video): ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
            <h4><?= mb_substr($video->title, 0, 20, 'UTF-8') . (mb_strlen($video->title, 'UTF-8') > 20 ? '...' : '') ?></h4>
            <figure class="effect-ming tm-video-item">
                <img src="<?= array_key_exists($video->id, $thumbnails) ? $thumbnails[$video->id]->path : '' ?>" alt="Image" class="img-fluid">
                <figcaption class="d-flex align-items-center justify-content-center">
                    <h2>Click to view</h2>
                    <a href="<?= Url::to(['video/index', 'id' => $video->id]) ?>">View more</a>
                </figcaption>
            </figure>
            <div class="tm-text-gray">
                <span class="tm-text-gray-light">Added: <?= $video->createdAt->format('d-m-Y h:i:s') ?></span><br>
                <span>Views: <?= number_format($video->counter) ?> </span><br>
                <span>Duration: <?= DurationView::format($video->duration) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div> <!-- row -->
    <div class="row tm-mb-90">
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
            <a href="<?= $page === 1 ? 'javascript:void(0);' : Url::to(['site/index', 'page' => $page - 1, 'orderDirection' => $orderDirection, 'orderBy' => $orderBy]) ?>" class="btn btn-primary tm-btn-prev mb-2 <?= $page === 1 ? 'disabled': ''?>">Previous page</a>

            <?php
                $generatePages = static function (int $from, int $to, int $currentPage) use ($orderDirection, $orderBy) {
                    $pages = [];
                    for ($i = $from; $i <= $to; $i++) {
                        $pages[] = [
                            'page' => $i,
                            'active' => $currentPage === $i,
                            'text' => (string)$i,
                            'link' => Url::to(['site/index', 'page' => $i, 'orderDirection' => $orderDirection, 'orderBy' => $orderBy]),
                        ];
                    }
                    return $pages;
                };

                if ($pages > 10) {
                    $emptyLink = [
                        'page' => null,
                        'active' => false,
                        'text' => '...',
                        'link' => null,
                    ];

                    $left = $page < 6 ? $generatePages(1, 6, $page) : array_merge($generatePages(1, 1, $page), [$emptyLink]);
                    $middle = ($page >= 6 && (($pages - $page) > 3)) ? $generatePages($page - 2, $page + 2, $page) : [];
                    $right = (($pages - $page) <= 3) ? $generatePages($pages - 5, $pages, $page) : array_merge([$emptyLink], $generatePages($pages, $pages, $page));

                    $linksToPages = array_merge($left, $middle, $right);
                } else {
                    $linksToPages = $generatePages(1, $pages, $page);
                }
            ?>
            <div class="tm-paging d-flex">
                <?php foreach ($linksToPages as $linkPage): ?>
                    <a href="<?= null === $linkPage['page'] ? 'javascript:void(0);' : $linkPage['link'] ?>" class="<?= $linkPage['active'] === true ? 'active' : '' ?> tm-paging-link <?= null === $linkPage['page'] ? 'disabled-link' : '' ?>"><?= $linkPage['text'] ?></a>
                <?php endforeach; ?>
            </div>

            <a href="<?= $page === $pages ? 'javascript:void(0);' : Url::to(['site/index', 'page' => $page + 1, 'orderDirection' => $orderDirection, 'orderBy' => $orderBy]) ?>" class="btn btn-primary tm-btn-next <?= $page === $pages ? 'disabled': ''?>">Next Page</a>
        </div>
    </div>
</div>