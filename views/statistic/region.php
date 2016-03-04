<?php

use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */

$this->title = 'Статистика';
?>
<div class="site-index">

    <?= $this->render('_tabs', [
        'tab' => 'region'
    ]);?>

    <div class="row">
        <div class="col-sm-12">
            <?php echo Highcharts::widget([
               'options' => [
                  'chart' => [
                      'type' => 'bar',
                  ],
                  'title' => ['text' => 'За весь час по районам'],
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
    </div>
</div>
