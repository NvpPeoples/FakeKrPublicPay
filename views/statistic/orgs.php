<?php

use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */

$this->title = 'Статистика';
?>
<?//= \yii\helpers\VarDumper::dumpAsString($rows)?>
<div class="site-index">

    <?= $this->render('_tabs', [
        'tab' => 'orgs'
    ]);?>

    <div class="row">
        <div class="col-sm-8 text-center">
            <big class="lead">Статистика за <?=$y?> рік</big>
        </div>
        <div class="col-sm-4">
            <?//067 606 97 61?>
            <div class="pull-right btn-group" role="group">
                <?php foreach($years_list as $id => $year):?>
                    <?php if ($id == $y) continue;?>
                    <?= Html::a($year, ['orgs', 'y' => $id], [
                        'class' => 'btn btn-default'
                    ])?>
                <?php endforeach?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?php echo Highcharts::widget([
               'options' => [
                  'chart' => [
                      'type' => 'bar',
                  ],
                  'title' => ['text' => 'Дитячі садочки/Школи по районах'],
                  'xAxis' => [
                      'categories' => $bar['categories']
                  ],
                  'legend' => [
                      'reversed' => true
                  ],
                  'yAxis' => [
                      'title' => ['text' => 'тис. грн.']
                  ],
                  'plotOptions' => [
                      'series' => [
                          'stacking' => 'normal'
                      ]
                  ],
                  'series' => $bar['series']
               ]
            ]);?>
        </div>
        <div class="col-sm-6">
            <?php echo Highcharts::widget([
               'options' => [
                  'chart' => [
                      'type' => 'pie',
                  ],
                  'title' => ['text' => 'Суми у розподілі по організаціям'],
                  'tooltip' => [
                        'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b><br/>{point.zz}'
                  ],
                  'series' => [[
                      'name' => 'Загальний відсоток',
                      'data' => $pie['data']
                  ]]
               ]
            ]);?>
        </div>
    </div>
    <h2>Найбільш витратні організації</h2>
    <table class="table table-stripped">
    <?php foreach($data['biggest'] as $row):?>
        <tr>
            <td><?= Html::encode($row['name'])?></td>
            <td class="text-right"><?= Yii::$app->formatter->asDecimal($row['sum']*100/$tot_summ, 2)?>%</td>
            <td class="text-right"><?= Yii::$app->formatter->asCurrency($row['sum'])?></td>
        </tr>
    <?php endforeach?>
    </table>
    <br/>
    <h2>Найбільш економні організації</h2>
    <table class="table table-stripped">
    <?php foreach($data['smallest'] as $row):?>
        <tr>
            <td><?= Html::encode($row['name'])?></td>
            <td class="text-right"><?= Yii::$app->formatter->asDecimal($row['sum']*100/$tot_summ, 2)?>%</td>
            <td class="text-right"><?= Yii::$app->formatter->asCurrency($row['sum'])?></td>
        </tr>
    <?php endforeach?>
    </table>

</div>
