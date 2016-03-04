<?php

use yii\db\Migration;

class m160222_170521_init_data extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->execute("
            create table region(
                code_reg integer primary key,
                name_reg text
            );
        ");
        $this->execute("
            create table school(
                id integer primary key,
                name text,
                boss text,
                code_reg integer,
                phone text
            );
        ");
        $this->createIndex('school_fk_reg', 'school', 'code_reg');
    }

    public function safeDown()
    {
        return true;
    }
}
