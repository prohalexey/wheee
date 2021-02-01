<?php

use yii\db\Migration;

class m210124_215812_create_row_counts extends Migration
{

    public function safeUp()
    {
        $this->createTable('{{%row_counts}}', [
            'relname' => $this->text()->notNull(),
            'reltuples' => $this->bigInteger(),
        ]);

        $this->execute("
            CREATE OR REPLACE FUNCTION adjust_count()
            RETURNS TRIGGER AS
            $$
               DECLARE
               BEGIN
               IF TG_OP = 'INSERT' THEN
                  EXECUTE 'UPDATE row_counts set reltuples = reltuples +1 where relname = ''' || TG_RELNAME || '''';
                  RETURN NEW;
               ELSIF TG_OP = 'DELETE' THEN
                  EXECUTE 'UPDATE row_counts set reltuples = reltuples -1 where relname = ''' || TG_RELNAME || '''';
                  RETURN OLD;
               END IF;
               END;
            $$
            LANGUAGE 'plpgsql';
        ");

        $this->execute("
            CREATE TRIGGER video_count BEFORE INSERT OR DELETE ON video
            FOR EACH ROW EXECUTE PROCEDURE adjust_count();
        ");
    }

    public function safeDown()
    {
        $this->execute("DROP TRIGGER video_count ON video;");

        $this->execute("DROP FUNCTION adjust_count();");

        $this->dropTable('{{%row_counts}}');
    }
}
