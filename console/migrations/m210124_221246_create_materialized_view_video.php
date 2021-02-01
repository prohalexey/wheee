<?php

use yii\db\Migration;

class m210124_221246_create_materialized_view_video extends Migration
{
    public function safeUp()
    {
        $this->execute("
            CREATE MATERIALIZED VIEW video_aggregated AS 
            SELECT 
                video.*, 
                video_views.counter,
                ROW_NUMBER() OVER (ORDER BY video.created_at ASC) AS rn_created_at,
                ROW_NUMBER() OVER (ORDER BY video_views.counter ASC) AS rn_counter
            FROM video
            JOIN video_views ON video_views.video_id = video.id;
        ");

        $this->createIndex('video_aggregated_rn_created_at_index', 'video_aggregated', 'rn_created_at', true);
        $this->createIndex('video_aggregated_rn_counter_index', 'video_aggregated', 'rn_counter', true);
    }

    public function safeDown()
    {
        $this->execute("DROP MATERIALIZED VIEW video_aggregated");
    }
}
