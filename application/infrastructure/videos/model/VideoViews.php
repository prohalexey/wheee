<?php

namespace application\infrastructure\videos\model;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $video_id
 * @property int $counter
 */
class VideoViews extends ActiveRecord
{
    public static function tableName()
    {
        return '{{video_views}}';
    }
}