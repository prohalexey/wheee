<?php

use yii\db\Migration;

class m210124_215631_create_video_views_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%video_views}}', [
            'id' => $this->primaryKey(),
            'video_id' => $this->integer()->notNull(),
            'counter' => $this->bigInteger()->notNull()->defaultValue(0)
        ]);

        $this->addForeignKey(
            'fk-video_views-video_id',
            '{{%video_views}}',
            'video_id',
            'video',
            'id',
            'CASCADE'
        );

        $this->execute('
            CREATE INDEX idx_video_views_counter
            ON {{%video_views}} USING btree (counter DESC) INCLUDE (video_id);
        ');
    }

    public function safeDown()
    {
        $this->dropTable('{{%video_views}}');
    }
}
