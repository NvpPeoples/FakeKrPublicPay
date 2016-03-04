<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Region;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Школи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schools-search">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['index'],
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
    ]);?>
        <div class="input-group col-sm-12">
            <?= Html::activeInput('text', $search, 'keyword', [
                'class' => 'form-control search-query',
                'placeholder' => 'пошук за назвою школи, призвіщем директора, тощо'
            ]);?>
            <span class="input-group-btn">
                <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span>', [
                    'class' => 'btn btn-primary'
                ]);?>
            </span>
        </div>
        <?php $list = ['' => 'Всі райони'] + ArrayHelper::map(Region::find()->all(), 'code_reg', 'name_reg');?>
        <?= $form->field($search, 'region', [
            'inlineRadioListTemplate' => "{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}"
        ])->inline()->radioList($list);?>
    <?php ActiveForm::end(); ?>
</div>
<div class="schools-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $prov,
        'tableOptions' => ['class' => 'table table-striped'],
        'rowOptions' => function($model, $key, $index, $grid) {
            return '';
        },
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model->name, ['detail', 'id' => $model->id]);
                }
            ],
            'boss',
            [
                'label' => date('Y').'рік',
                'contentOptions' => [
                    'class' => 'text-right lead',
                ],
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model->getCntOfCurYearPays(), ['pays', 'ow' => $model->id]);
                }
            ],
            [
                'label' => 'Платежів',
                'contentOptions' => [
                    'class' => 'text-right lead',
                ],
                'format' => 'raw',
                'value' => function($model) {
                    return $model->getCntOfPays();
                }
            ],
        ],
    ]); ?>

</div>
