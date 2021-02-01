<?php

namespace application\controllers;

use application\infrastructure\videos\policy\ThumbnailPathPolicy;
use application\infrastructure\videos\repository\VideoRepository;

use Domain\Videos\UseCase\UpdateVideoViewCounter;

use Yii;
use yii\log\Logger;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class VideoController extends Controller
{
    public function actionIndex()
    {

        $request = Yii::$app->request;

        // Todo: Load from DI container VideoRepositoryContract
        $videoRepository = new VideoRepository(Yii::$app->db);

        $id = (int)$request->get('id',1);
        $id = abs($id);

        // Cannot open page;
        try {
            $video = $videoRepository->find($id);

            // Todo: Load from DI container like ThumbnailPathPolicyContract
            $thumbnail = (new ThumbnailPathPolicy())->getThumbnail($video);
        } catch (\Throwable $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            throw new NotFoundHttpException('Video not found');
        }

        // We can skip the error even if there was an error here,
        // These functions are best used after the request completes
        // Event for fastcgi_finish_request()

        try {
            (new UpdateVideoViewCounter($videoRepository))->execute($video);
        } catch (\Throwable $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_WARNING);
        }

        return $this->render('index', compact(
            'video',
            'thumbnail'
        ));
    }
}