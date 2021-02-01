<?php

use Domain\Videos\Entity\Video;
use common\helpers\objectViews\DurationView;

/** @var Video $video  */


?>
<div class="container-fluid tm-container-content tm-mt-60">
    <div class="row mb-4">
        <h2 class="col-12 tm-text-primary"><?= $video->title ?></h2>
    </div>
    <div class="row tm-mb-90">
        <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12">
            <video autoplay muted loop controls id="tm-video">
                <source src="/video/hero.mp4" type="video/mp4">
            </video>
        </div>
        <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
            <div class="tm-bg-gray tm-video-details">
                <p class="mb-4">
                    <img src="<?= $thumbnail->path ?? '' ?>" alt="Image" class="img-fluid">
                    <span">Added:<?= $video->createdAt->format('d-m-Y h:i:s') ?> </span><br>
                    <span>Views: <?= number_format($video->counter) ?> </span><br>
                    <span>Duration: <?= DurationView::format($video->duration) ?></span>
                </p>
            </div>
        </div>
    </div>
</div> <!-- container-fluid, tm-container-content -->