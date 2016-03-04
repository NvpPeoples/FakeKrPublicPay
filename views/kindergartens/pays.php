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
$this->params['breadcrumbs'][] = [
    'label' => \yii\helpers\StringHelper::truncate($kindergarten->name, 70),
    'url'   => ['detail', 'id' => $kindergarten->id]
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
            'size' =>  '180%',
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

<div class="kindergarten-search">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['pays', 'ow' => $kindergarten->id],
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
    ]);?>
    <div class="well well-sm">
        <div class="row">
            <div class="col-sm-8">
                <?= $form->field($search, 'year', [
                    'inlineRadioListTemplate' => "{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}"
                ])->inline()->radioList($years_list);?>
            </div>
            <div class="col-sm-4">
                <?= Html::submitButton('<span class="glyphicon glyphicon-filter"></span> фільтрувати', [
                    'class' => 'btn btn-primary pull-right'
                ]);?>
            </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="pays-index">

    <big>Плетежі:</big>

    <?= GridView::widget([
        'dataProvider' => $prov,
        'tableOptions' => ['class' => 'table table-striped'],
        'rowOptions' => function($model, $key, $index, $grid) {
            return '';
        },
        'columns' => [
            'who',
            'desc',
            [
                'attribute' => 'summ',
                'contentOptions' => [
                    'class' => 'text-right lead',
                ],
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a(Yii::$app->formatter->asCurrency($model->summ), null, [
                        'href' => 'javascript:void(0);',
                        'class' => 'bn-pay',
                        'data-pay' => $model->id
                    ]);
                }
            ],
        ],
    ]); ?>

    <?php
        if ($search->year == date('Y')) {
            $inst = $gauge;
            $inst['yAxis']['max'] = round($kindergarten->getLimitOfYear($search->year));
            if ($inst['yAxis']['max'] > 0) {
                $inst['series'][0]['data'][0] = $kindergarten->getSumOfCurYearPays();
            } else {
                $inst['series'][0]['data'][0] = -1;
            }
            echo Highcharts::widget(['options' => $inst]);
        }
    ?>

</div>
<?php Modal::begin([
    'header' => 'Деталі платежа',
    'size' => Modal::SIZE_LARGE,
    'options' => [
        'id' => 'idModal',
    ]
])?>
<?php Modal::end();?>

<?php
$url_tmpl = Url::to(['/pays/ajax-detail', 'id' => '_id_', 't' => 'k']);
$this->registerJs($code=<<<EOT
    $(".bn-pay").on("click", function(){
        $("#idModal").modal('show');
        var url = "{$url_tmpl}".replace(/(_id_)/, $(this).data('pay'))
        $("#idModal").find('.modal-body').load(url)
    });
EOT
, View::POS_READY, 'modal');?>
