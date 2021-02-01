<?php

namespace application\controllers;

use application\infrastructure\videos\policy\ThumbnailPathPolicy;
use application\infrastructure\videos\repository\VideoRepository;

use Yii;
use yii\web\Controller;

use Domain\Videos\Repository\VideoRepositoryContract;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $request = Yii::$app->request;

        // Todo: Load from DI container VideoRepositoryContract
        $videoRepository = new VideoRepository(Yii::$app->db);

        $itemsPerPage = Yii::$app->params['itemsPerPageSiteIndex'];

        $orderBy = $this->getOrderBy();
        $orderDirection = $this->getOrderDirection();

        $page = (int)$request->get('page',1);
        $page = abs($page);
        if ($page < 1) {
            $page = 1;
        }

        $total = $videoRepository->getTotal();

        if ($orderDirection === VideoRepositoryContract::ORDER_DIRECTION_ASC) {
            $offset = ($page - 1) * $itemsPerPage;
            if ($offset > $total) {
                $offset = $total - $itemsPerPage;
            }
        } else {
            $offset = $total - ($page - 1) * $itemsPerPage;
        }

        $pages = (int)ceil($total / $itemsPerPage);

        $videos = $videoRepository->getPaginated($orderBy, $orderDirection, $itemsPerPage, $offset);

        // Todo: Load from DI container like ThumbnailPathPolicyContract
        $thumbnailsPolicy = new ThumbnailPathPolicy();
        $thumbnails = $thumbnailsPolicy->getThumbnails($videos);

        return $this->render('index', compact(
            'videos',
            'thumbnails',
            'pages',
            'total',
            'page',
            'orderBy',
            'orderDirection',
        ));
    }

    public function actionError()
    {
        return $this->render('error');
    }

    // Todo: Move logic to standart Yii2 paginator
    private function getOrderBy()
    {
        $orderBy = Yii::$app->request->get('orderBy', VideoRepositoryContract::ORDER_BY_DATE);
        $orderBy = strtolower($orderBy);

        $allowedOrderBy = [
            VideoRepositoryContract::ORDER_BY_VIEWS,
            VideoRepositoryContract::ORDER_BY_DATE,
        ];
        if (!in_array($orderBy, $allowedOrderBy, true)) {
            $orderBy = VideoRepositoryContract::ORDER_BY_DATE;
        }

        return $orderBy;
    }

    // Todo: Move logic to standart Yii2 paginator
    private function getOrderDirection()
    {
        $orderDirection = Yii::$app->request->get('orderDirection', VideoRepositoryContract::ORDER_DIRECTION_DESC);
        $orderDirection = strtoupper($orderDirection);

        $allowedOrderDirection = [
            VideoRepositoryContract::ORDER_DIRECTION_ASC,
            VideoRepositoryContract::ORDER_DIRECTION_DESC,
        ];
        if (!in_array($orderDirection, $allowedOrderDirection, true)) {
            $orderDirection = VideoRepositoryContract::ORDER_DIRECTION_DESC;
        }

        return $orderDirection;
    }
}
