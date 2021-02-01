<?php

namespace application\infrastructure\videos\repository;

use application\infrastructure\videos\model\Video as VideoModel;
use yii\db\Connection;

use Domain\Videos\Entity\Video;
use Domain\Videos\Exception\EntityNotFoundException;
use Domain\Videos\Repository\VideoRepositoryContract;

class VideoRepository implements VideoRepositoryContract
{
    protected Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function find(int $id): ?Video
    {
        $model = VideoModel::find()
            ->with('videoViews')
            ->where(['id' => $id])
            ->one();

        if (!$model) {
            throw new EntityNotFoundException(
                sprintf('There is no video with id %d', $id)
            );
        }

        return Video::fromArray(array_merge(
            $model->toArray(),
            ['counter' => $model->videoViews->counter]
        ));
    }

    public function incrementViewCounter(Video $video)
    {
        $transaction = $this->db->beginTransaction();

        try {
            $this->db->createCommand("
                UPDATE video_views 
                SET counter = counter + 1 
                WHERE video_id = :video_id
            ", [
                ':video_id' => $video->id
            ])->execute();

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();

            throw $e;
        }
    }

    public function getTotal(): int
    {
        return $this->db->createCommand("
            SELECT 
               reltuples 
            FROM row_counts 
            WHERE relname = 'video'
        ")->queryScalar();
    }

    public function getPaginated(string $orderBy, string $orderDirection, int $limit, int $offset = 0): array
    {
        if ($orderBy === VideoRepositoryContract::ORDER_BY_DATE) {
            $column = 'rn_created_at';
        } elseif ($orderBy === VideoRepositoryContract::ORDER_BY_VIEWS) {
            $column = 'rn_counter';
        } else {
            throw new \InvalidArgumentException('Unknown orderBy type');
        }

        $sign = $orderDirection === VideoRepositoryContract::ORDER_DIRECTION_ASC ? '>=' : '<=';

        return $this->loadFromDb("
            SELECT
                id,
                title,
                created_at,
                duration,
                counter
            FROM video_aggregated
            WHERE {$column} {$sign} :offset
            ORDER BY {$column} {$orderDirection}
            LIMIT :limit
        ", [
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }

    protected function loadFromDb(string $sql, array $values): array
    {
        $collection = [];

        $models = $this->db->createCommand($sql)->bindValues($values)->queryAll();
        foreach ($models as $data) {
            $collection[] = Video::fromArray($data);
        }

        return $collection;
    }
}