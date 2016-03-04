<?php

use yii\db\Migration;

class m160222_183506_pays extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->execute("
            create table pays(
                id integer primary key AUTOINCREMENT,
                id_school integer,
                id_kindg  integer,
                yy integer,
                qq integer,
                who text,
                desc test,
                summ real
            );
        ");
    }

    public function safeDown()
    {
    }
}
