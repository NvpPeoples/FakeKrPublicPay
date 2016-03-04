<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пошук на сайті';
$this->params['breadcrumbs'][] = [
    'label' => 'Пошук',
    'url'   => ['index']
];
?>

<div class="glob-search">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['index'],
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
    ]);?>
    <div class="well well-sm">
        <div class="input-group col-sm-12">
            <?= Html::activeInput('text', $search, 'keyword', [
                'class' => 'form-control search-query',
                'placeholder' => 'пошук за призначенням, отримувачем, закладом ...'
            ]);?>
            <span class="input-group-btn">
                <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span>', [
                    'class' => 'btn btn-primary'
                ]);?>
            </span>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="pays-index">

    <?= ListView::widget([
        'dataProvider' => $prov,
        'itemView' => function ($model, $key, $index, $widget) {
            if ($model['summ'] > 0) {
                return $this->render('_search_item_pay',['model' => $model]);
            } else if ($model['tp'] == 's') {
                return $this->render('_search_item_school',['model' => $model]);
            } else if ($model['tp'] == 'k') {
                return $this->render('_search_item_kind',['model' => $model]);
            }
        }
    ]); ?>

</div>
