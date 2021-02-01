<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use Faker\Factory as FakerFactory;

class SeedController extends Controller
{
    public function actionIndex()
    {
        $batchNum = 10;
        $batchSize = 100000;

        $faker = FakerFactory::create();
        $db = Yii::$app->db;

        // Clear tables
        $db->createCommand("TRUNCATE TABLE public.video RESTART IDENTITY CASCADE;")->execute();
        $db->createCommand("DELETE FROM row_counts WHERE relname = 'video'")->execute();

        // Temporarily disable trigger due to huge impact on insert operations
        $db->createCommand("ALTER TABLE public.video DISABLE TRIGGER video_count;")->execute();

        $id = 1;

        for ($b = 1; $b <= $batchNum; $b++) {

            echo vsprintf("Start batch %d from %d batches with %d items \n", [$b, $batchNum, $batchSize]);

            $videos = [];
            $videoViews = [];

            for ($i = 1; $i <= $batchSize; $i++) {
                $videos[] = [
                    $this->randString(30),
                    $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d h:i:s'),
                    random_int(5, 7250),
                ];

                $videoViews[] = [
                    $id,
                    random_int(5, 5_000_000_000),
                ];

                $id++;
            }

            try {
                $transaction = $db->beginTransaction();

                $db->createCommand()->batchInsert('video', ['title', 'created_at', 'duration'], $videos)->execute();
                $db->createCommand()->batchInsert('video_views', ['video_id', 'counter'], $videoViews)->execute();

                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
            }
        }

        $db->createCommand("ALTER TABLE public.video ENABLE TRIGGER video_count;")->execute();

        $db->createCommand("INSERT INTO row_counts (relname, reltuples) VALUES ('video', (SELECT count(id) FROM public.video));")->execute();
    }

    private function randString(int $len)
    {
        static $preGenerated = '';

        $preGeneratedLen = 2000;

        if (empty ($preGenerated)) {

            $characters = 'abcdefghijklmnopqrstuvwxyz';
            $charLength = strlen($characters) - 1;
            $minWordLen = 4;

            $stringLen = 0;
            for ($a = 0; $a < $preGeneratedLen / $minWordLen; $a++) {
                $bTimes = random_int(4, 10);
                for ($b = 0; $b <= $bTimes; $b++) {
                    $preGenerated .= $characters[random_int(0, $charLength)];
                    $stringLen++;
                    if ($stringLen === $preGeneratedLen) {
                        break 2;
                    }
                }
                $preGenerated .= ' ';
            }
        }

        return ucfirst(
            trim(
                substr($preGenerated, random_int(0, $preGeneratedLen - $len), $len)
            )
        );
    }
}