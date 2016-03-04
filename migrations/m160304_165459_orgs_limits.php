<?php

use yii\db\Migration;

class m160304_165459_orgs_limits extends Migration
{
    public function safeUp()
    {
        $this->execute("
            create table school_limit(
                id integer primary key,
                id_parent integer,
                yy integer,
                summ real
            );
        ");

        $this->execute("
            create table kindergarten_limit(
                id integer primary key,
                id_parent integer,
                yy integer,
                summ real
            );
        ");
        $columns = ['id_parent', 'yy', 'summ'];
        $cur_year = date('Y');
        foreach(['school', 'kindergarten'] as $who) {
            $field = $who == 'school' ? 'id_school' : 'id_kindg';
            for($i = date('Y')-2; $i<=date('Y'); $i++) {
                $ins = [];
                $rows = (new \yii\db\Query)
                    ->select(['t.id as id', 'sum(summ) as sm'])
                    ->from(['t' => $who])
                    ->leftJoin(['t1' => 'pays'], [
                        't1.'.$field => new \yii\db\Expression('t.id'),
                        't1.yy' => $i
                    ])
                    ->groupBy('t.id')
                    ->all();
                foreach($rows as $row) {
                    if ($i == $cur_year) {
                        $rnd = rand(150, 400);
                        $sum = $row['sm']*($rnd/100);
                    } else {
                        $rnd = rand(100, 115);
                        $sum = $row['sm']*($rnd/100);
                        if ($sum > $row['sm']) {
                            $sum = $row['sm'];
                        }
                    }
                    $ins[] = [
                        $row['id'],
                        $i,
                        $sum
                    ];
                }
                $this->getDb()->createCommand()->batchInsert(sprintf("%s_limit", $who), $columns, $ins)->execute();
            }
        }
    }

    public function safeDown()
    {
        $this->execute('drop table school_limit');
        $this->execute('drop table kindergarten_limit');
    }
}
