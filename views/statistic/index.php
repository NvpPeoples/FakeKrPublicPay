<?php

use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */

$this->title = 'Статистика';
?>
<div class="site-index">

    <?= $this->render('_tabs', [
        'tab' => 'index'
    ]);?>

    <div class="row">
        <div class="col-sm-6">
            <?php echo Highcharts::widget([
               'options' => [
                  'chart' => [
                      'type' => 'bar',
                  ],
                  'title' => ['text' => 'Сума платежів за кварталами'],
                  'xAxis' => [
                     'categories' => ['I', 'II', 'III', 'IV']
                  ],
                  'yAxis' => [
                     'title' => ['text' => 'тис. грн.']
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
                  'title' => ['text' => 'Суми витрачені вцілому'],
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
            <?php echo Highcharts::widget([
               'options' => [
                  'chart' => [
                      'type' => 'column',
                  ],
                  'title' => ['text' => 'Середній платіж за рік'],
                  'xAxis' => [
                     'categories' => $column['categories'],
                     'visible'    => false,
                  ],
                  'yAxis' => [
                     'title' => ['text' => 'тис. грн.']
                  ],
                  'tooltip' => [
                        'formatter' => new \yii\web\JsExpression("function(){return this.series.name+'рік = <b>'+this.y+'</b>тис.грн.';}"),
                  ],
                  'series' => $column['series']
               ]
            ]);?>
</div>
