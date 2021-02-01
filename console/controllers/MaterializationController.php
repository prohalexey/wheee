<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class MaterializationController extends Controller
{
    // Put this command to the crontab
    // It took about 5 minutes for 20 million lines
    // So 30 * * * * would be nice
    public function actionIndex()
    {
        Yii::$app->db->createCommand('REFRESH MATERIALIZED VIEW video_aggregated;');
    }
}