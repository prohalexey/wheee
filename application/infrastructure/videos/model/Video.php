<?php

namespace application\infrastructure\videos\model;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property int $duration
 * @property VideoViews $videoViews
 */
class Video extends ActiveRecord
{
    public static function tableName()
    {
        return '{{video}}';
    }

    public function getVideoViews()
    {
        return $this->hasOne(VideoViews::class, ['video_id' => 'id']);
    }
}