<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Kindergarten;
use app\models\School;
use app\models\Pays;
use app\models\Region;

class StatisticController extends Controller
{
    public function actionIndex()
    {
        $bar = [];
        $pie = [];
        $column = [];
        $rows = Pays::find()
            ->select(['yy', 'qq', 'sum(summ) sum', 'count(*) cnt'])
            ->groupBy(['yy', 'qq'])
            ->asArray()
            ->all();
        $bar['series'] = [];
        $pie['data'] = [];
        $column['categories'] = [];
        $column['series']     = [];
        $tot_summ = 0;
        foreach($rows as $row) {
            if (!array_key_exists($row['yy'], $bar['series'])) {
                $bar['series'][$row['yy']] = [
                    'data' => [],
                    'name' => $row['yy']
                ];
            }
            $bar['series'][$row['yy']]['data'][] = round($row['sum']/1000);

            if (!array_key_exists($row['yy'], $pie['data'])) {
                $pie['data'][$row['yy']] = [
                    'name' => $row['yy'],
                    'val'  => 0,
                    'y'    => 0,
                ];
            }
            $val = round($row['sum']/1000);
            $pie['data'][$row['yy']]['val'] += $val;
            $tot_summ += $val;

            if (!array_key_exists($row['yy'], $column['series'])) {
                $column['series'][$row['yy']] = [
                    'data' => [],
                    'name' => $row['yy'],
                    'summ' => 0,
                    'cnt'  => 0,
                ];
            }
            $column['series'][$row['yy']]['summ'] += round($row['sum']/1000);
            $column['series'][$row['yy']]['cnt'] += $row['cnt'];
        }

        $column['categories'] = array_keys($bar['series']);
        $column['series'] = array_values($column['series']);
        array_walk($column['series'], function(&$row) {
            $row['data'][0] = round($row['summ']/$row['cnt']);
        });

        $bar['series'] = array_values($bar['series']);

        $pie['data']   = array_values($pie['data']);
        array_walk($pie['data'], function(&$row) use($tot_summ) {
            $row['y'] = round($row['val']*100/$tot_summ);
        });
        return $this->render('index', [
            'bar'    => $bar,
            'pie'    => $pie,
            'column' => $column,
        ]);
    }

    public function actionRegion()
    {
        $bar = [];

        $rows = \yii\helpers\ArrayHelper::merge( 
            Pays::find()
                ->alias('t')
                ->select(['s.code_reg r', 't.yy yy', 'sum(t.summ) sum', 'count(*) cnt'])
                ->innerJoin(['s' => School::tableName()], 't.id_school=s.id')
                ->groupBy(['s.code_reg', 't.yy'])
                ->asArray()
                ->all(),

            Pays::find()
                ->alias('t')
                ->select(['s.code_reg r', 't.yy yy', 'sum(t.summ) sum', 'count(*) cnt'])
                ->innerJoin(['s' => Kindergarten::tableName()], 't.id_kindg=s.id')
                ->groupBy(['s.code_reg', 't.yy'])
                ->asArray()
                ->all()
        );

        $bar['series'] = [];
        $bar['categories'] = [];

        foreach($rows as $row) {
            if (!array_key_exists($row['yy'], $bar['series'])) {
                $bar['series'][$row['yy']] = [
                    'data' => [],
                    'name' => $row['yy']
                ];
            }
            if (!array_key_exists($row['r'], $bar['series'][$row['yy']]['data'])) {
                $bar['series'][$row['yy']]['data'][$row['r']] = 0;
            }
            $bar['series'][$row['yy']]['data'][$row['r']] += round($row['sum']/1000);
            $bar['categories'][$row['r']] = true;
        }
        $bar['series'] = array_values($bar['series']);
        $bar['categories'] = array_keys($bar['categories']);

        $cats = $bar['categories'];
        array_walk($bar['series'], function(&$row) use($cats) {
            $data = [];
            foreach($cats as $cat) {
                $data[] = array_key_exists($cat, $row['data']) ? $row['data'][$cat] : 0;
            }
            $row['data'] = $data;
        });

        $regions = \yii\helpers\ArrayHelper::map(
            Region::find()
                ->select(['code_reg', 'name_reg'])
                ->asArray()
                ->all(),
            'code_reg', 'name_reg'
        );

        array_walk($bar['categories'], function(&$row) use($regions){
            $row = $regions[$row];
        });

        return $this->render('region', [
            'bar'  => $bar,
        ]);
    }

    public function actionOrgs($y = 2016)
    {
        $rows = \yii\helpers\ArrayHelper::merge( 
            Pays::find()
                ->alias('t')
                ->select(['s.code_reg r', 't.yy yy', new \yii\db\Expression('"s" typ'), 'sum(t.summ) sum', 'count(*) cnt'])
                ->innerJoin(['s' => School::tableName()], 't.id_school=s.id')
                ->where(['t.yy' => $y])
                ->groupBy(['t.yy'])
                ->asArray()
                ->all(),

            Pays::find()
                ->alias('t')
                ->select(['s.code_reg r', 't.yy yy', new \yii\db\Expression('"k" typ'), 'sum(t.summ) sum', 'count(*) cnt'])
                ->innerJoin(['s' => Kindergarten::tableName()], 't.id_kindg=s.id')
                ->where(['t.yy' => $y])
                ->groupBy(['t.yy'])
                ->asArray()
                ->all()
        );
        $pie = [];
        $pie['data'] = [];
        $tot_summ = 0;
        foreach($rows as $row) {

            if (!array_key_exists($row['typ'], $pie['data'])) {
                $pie['data'][$row['typ']] = [
                    'name' => $row['typ'] == 's' ? 'Школи' : 'Дитячі садочки',
                    'val'  => 0,
                    'y'    => 0,
                ];
            }
            $val = round($row['sum']/1000);
            $pie['data'][$row['typ']]['val'] += $val;
            $tot_summ += $val;
        }

        $pie['data']   = array_values($pie['data']);
        array_walk($pie['data'], function(&$row) use($tot_summ) {
            $row['y'] = round($row['val']*100/$tot_summ);
        });

        $cmd = Pays::find()
                ->alias('t')
                ->select(['s.name as name', new \yii\db\Expression('"s" typ'), 'sum(t.summ) sum'])
                ->innerJoin(['s' => School::tableName()], 't.id_school=s.id')
                ->where(['t.yy' => $y])
                ->groupBy(['s.name']);

        $cmd1 = Pays::find()
                ->alias('t')
                ->select(['s.name as name', new \yii\db\Expression('"k" typ'), 'sum(t.summ) sum'])
                ->innerJoin(['s' => Kindergarten::tableName()], 't.id_kindg=s.id')
                ->where(['t.yy' => $y])
                ->groupBy(['s.name']);
        $sql = $cmd->createCommand()->getRawSql()
            .' union all '
            .$cmd1->createCommand()->getRawSql()
            .' order by sum desc'
            .' limit 10';

        $tot_summ = Pays::find()->select('sum(summ) as sum')->scalar();

        $data = [];
        $data['biggest'] = Yii::$app->getDb()->createCommand($sql)->queryAll();

        $sql = $cmd->createCommand()->getRawSql()
            .' union all '
            .$cmd1->createCommand()->getRawSql()
            .' order by sum asc'
            .' limit 10';
        $data['smallest'] = Yii::$app->getDb()->createCommand($sql)->queryAll();

        $bar = [];

        $rows = \yii\helpers\ArrayHelper::merge( 
            Pays::find()
                ->alias('t')
                ->select(['s.code_reg r', new \yii\db\Expression('"s" typ'), 'sum(t.summ) sum'])
                ->innerJoin(['s' => School::tableName()], 't.id_school=s.id')
                ->where(['t.yy' => $y])
                ->groupBy(['s.code_reg', 'typ'])
                ->asArray()
                ->all(),

            Pays::find()
                ->alias('t')
                ->select(['s.code_reg r', new \yii\db\Expression('"k" typ'), 'sum(t.summ) sum'])
                ->innerJoin(['s' => Kindergarten::tableName()], 't.id_kindg=s.id')
                ->where(['t.yy' => $y])
                ->groupBy(['s.code_reg', 'typ'])
                ->asArray()
                ->all()
        );

        $bar['series'] = [];
        $bar['categories'] = [];

        foreach($rows as $row) {
            if (!array_key_exists($row['typ'], $bar['series'])) {
                $bar['series'][$row['typ']] = [
                    'data' => [],
                    'name' => $row['typ'] == 's' ? 'Школи' : 'Дитячі садочки'
                ];
            }
            if (!array_key_exists($row['r'], $bar['series'][$row['typ']]['data'])) {
                $bar['series'][$row['typ']]['data'][$row['r']] = 0;
            }
            $bar['series'][$row['typ']]['data'][$row['r']] += round($row['sum']/1000);
            $bar['categories'][$row['r']] = true;
        }
        $bar['series'] = array_values($bar['series']);
        $bar['categories'] = array_keys($bar['categories']);

        $cats = $bar['categories'];
        array_walk($bar['series'], function(&$row) use($cats) {
            $data = [];
            foreach($cats as $cat) {
                $data[] = array_key_exists($cat, $row['data']) ? $row['data'][$cat] : 0;
            }
            $row['data'] = $data;
        });

        $regions = \yii\helpers\ArrayHelper::map(
            Region::find()
                ->select(['code_reg', 'name_reg'])
                ->asArray()
                ->all(),
            'code_reg', 'name_reg'
        );

        array_walk($bar['categories'], function(&$row) use($regions){
            $row = $regions[$row];
        });

        $years_list = Yii::$app->getDb()->cache(function($db){
            return \yii\helpers\ArrayHelper::map(
                Pays::find()
                    ->distinct()
                    ->select(['yy as id', new \yii\db\Expression('yy||" рік" as title')])
                    ->orderBy('id desc')
                    ->asArray()
                    ->all(),
                'id', 'title');
        }, 600);

        return $this->render('orgs', [
            //
            'y'           => $y,
            'years_list' => $years_list,
            'tot_summ'    => $tot_summ,
            'data'        => $data,
            'bar'         => $bar,
            'pie'         => $pie,
        ]);
    }

}
