<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\web\View;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Region;
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Дитячі садочки';
$this->params['breadcrumbs'][] = [
    'label' => 'Дитячі садочки',
    'url'   => ['index']
];
HighchartsAsset::register($this)->withScripts(['highcharts-more', 'modules/solid-gauge']);
$gauge = [
        'chart' =>[
            'type' => 'solidgauge',
            'backgroundColor' => 'transparent',
            'height' => 250
        ],

        'title' => null,

        'pane' => [
            'center'=> ['50%', '90%'],
            'size' =>  '140%',
            'startAngle' => -90,
            'endAngle' =>  90,
            'background' => [
                'innerRadius' => '60%',
                'outerRadius' => '100%',
                'shape' => 'arc'
            ]
        ],
        'yAxis' => [
            'min' => 0,
            'max' => 200,
            'title' => [
                'text' => 'Використаний обсяг'
            ],
            'stops' => [
                [0.1, '#DF5353'], // red
                [0.5, '#DDDF0D'], // yellow
                [0.9, '#55BF3B'], // green
            ],
            'lineWidth' => 0,
            'minorTickInterval' => null,
            'tickPixelInterval' => 100,
            'tickWidth' => 0,
            'title' => [
                'y' => -70
            ],
            'labels' => [
                'y' => 16
            ]
        ],

        'credits' => [
            'enabled' => false
        ],
        'tooltip' => [
            'enabled' => false
        ],
        'plotOptions' => [
            'solidgauge' => [
                'dataLabels' => [
                    'y' => 5,
                    'borderWidth' => 0,
                    'useHTML' => true
                ]
            ]
        ],
        'series' => [[
            'name' => 'Speed',
            'data' => [95],
            'dataLabels' => [
                'formatter' => new \yii\web\JsExpression("function(){ if (this.y==-1) { return '0%'}; return '<div style=\"text-align:center\"><span style=\"font-size:35px;color:"
                    ."((Highcharts.theme && Highcharts.theme.contrastTextColor) || \'black\')\">'+Math.round(this.y*100/this.series.yAxis.max)+'</span><br/>"
                    ."<span style=\"font-size:20px;color:silver\">%</span></div>';}")
            ],
            'tooltip' => [
                'valueSuffix' => ' %'
            ]
        ]]
];
?>
<h1><?= $kindergarten->name?></h1>
<div class="kindergarten-search">
    <div class="row lead">
        <div class="col-sm-6">
            <dl>
                <dt><?= $kindergarten->getAttributeLabel('boss')?></dt>
                <dd><?= Html::encode($kindergarten->boss)?></dd>
            </dl>
            <dl>
                <dt><?= $kindergarten->getAttributeLabel('code_reg')?></dt>
                <dd><?= Html::encode($kindergarten->region->name_reg)?></dd>
            </dl>
        </div>
        <div class="col-sm-6">
                <dt><?= $kindergarten->getAttributeLabel('phone')?></dt>
                <dd><?= $kindergarten->phone?></dd>
        </div>
    </div>
</div>
<div class="pays-index">
    <?php $ostat = $stat?>
    <?php krsort($stat);?>
    <?php while($portion = array_splice($stat, 0, 3)):?>
    <div class="row">
        <?php foreach($portion as $idx => $row):?>
        <div class="col-sm-4">
            <div class=" bg-success" style="padding:0.5em">
            <h2><?= $row['year']?>рік</h2>

            <div class="text-center"><span class="badge" style="font-size:150%"> <?= Yii::t('app', '{delta, plural, =1{1 pay} pays{# pays}}', ['delta' => $row['cnt']])?></span></div>
            <br/>
            <div class="text-center"><span class="label label-primary" style="font-size:250%"><?= Yii::$app->formatter->asCurrency($row['summ'])?></span></div>
            <br/>
            <div class="text-center">Заплановано: <?= Yii::$app->formatter->asCurrency($kindergarten->getLimitOfYear($row['year']))?></div>
            <?php
                $inst = $gauge;
                $inst['yAxis']['max'] = round($kindergarten->getLimitOfYear($row['year']));
                if ($inst['yAxis']['max'] > 0) {
                    $inst['series'][0]['data'][0] = round($row['summ']);
                } else {
                    $inst['series'][0]['data'][0] = -1;
                }
                echo Highcharts::widget(['options' => $inst]);?>
            <p class="text-center"><?= Html::a('переглянути <i class="glyphicon glyphicon-chevron-right"></i>', [
                'pays', 'ow' => $kindergarten->id, 'PaysOnKindergartenSearch[year]' => $row['year']
            ], [
                    'class' => 'btn btn-default'
                ]);?></p>
        </div>
        </div>
        <?php endforeach?>
    </div>
    <br/>
    <?php endwhile?>

    <?php
    $categoris = [];
    $series = [];
    foreach($ostat as $row) {
        $categories[] = $row['year'];
        $series[] = [
            'data' => [round($row['summ'])],
            'name' => $row['year'],
            'summ' => Yii::$app->formatter->asCurrency($row['summ']),
            'cnt'  => $row['cnt'],
        ];
    }
    ?>

    <?php echo Highcharts::widget([
       'options' => [
          'chart' => [
              'type' => 'column',
          ],
          'title' => ['text' => 'За всі роки'],
          'xAxis' => [
             'categories' => $categories,
             'visible'    => false,
          ],
          'yAxis' => [
             'title' => ['text' => 'грн.']
          ],
          'tooltip' => [
                'formatter' => new \yii\web\JsExpression("function(){return this.series.name+'рік = <b>'+this.series.options.summ+'</b>';}"),
          ],
          'series' => $series
       ]
    ]);?>

</div>
