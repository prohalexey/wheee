<?php

use yii\db\Migration;

class m210124_215442_create_video_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%video}}', [
            'id' => $this->primaryKey(),  // bigInteger
            'title' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
            'duration' => $this->bigInteger()->notNull(),
        ]);

        $this->execute('
            CREATE INDEX idx_video_created_at_desc 
            ON {{%video}} (created_at DESC)
        ');
    }

    public function safeDown()
    {
        $this->dropTable('{{%video}}');
    }
}
