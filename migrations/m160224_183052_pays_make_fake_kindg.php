<?php

use yii\db\Migration;
use Faker\Provider\uk_UA;
use app\models\Kindergarten;
use app\models\Pays;

class m160224_183052_pays_make_fake_kindg extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $faker = Faker\Factory::create('uk_UA');
        $ids = Kindergarten::find()->select('id')->column();
        $min_id = min($ids);
        $max_id = max($ids);
        $cnt = $tot = 2048;
        $rows = [];
        $columns = [
            'id_kindg', 'who', 'desc', 'summ', 'yy', 'qq'
        ];
        $rnd = [];
        for($i = date('Y')-2; $i<=date('Y'); $i++) {
            $rnd[$i] = [
                'min' => rand(1000, 10000),
                'max' => rand(300000, 400000),
            ];
        }
        do {
            $id = rand($min_id, $max_id);
            if (!in_array($id, $ids)) continue;
            $year = rand(date('Y')-2, date('Y')-1);
            if ($cnt < $tot/(2*4+1)) {
                $year = date('Y');
            }
            $rows[] = [
                $id,
                ($cnt % 2 ? 'ПП ' : 'ФОП ').$faker->name,
                $faker->realText(80),
                $faker->randomFloat(2, $rnd[$year]['min'], $rnd[$year]['max']),
                $year,
                ($year==date('Y') ? 1 : rand(1,4))
            ];
            if ($cnt % 300 == 0) {
                $this->getDb()->createCommand()->batchInsert(Pays::tableName(), $columns, $rows)->execute();
                $rows = [];
                echo '*';
            }
            $cnt--;
        } while($cnt > 0);
        if (!empty($rows)) {
            $this->getDb()->createCommand()->batchInsert(Pays::tableName(), $columns, $rows)->execute();
        }
    }

    public function safeDown()
    {
    }
}
