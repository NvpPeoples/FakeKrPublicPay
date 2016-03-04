<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\web\View;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Region;

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

    <?= GridView::widget([
        'dataProvider' => $prov,
        'tableOptions' => ['class' => 'table table-striped'],
        'rowOptions' => function($model, $key, $index, $grid) {
            return '';
        },
        'columns' => [
            [
                'label' => 'Платник',
                'format' => 'raw',
                'value' => function($model) {
                    $org = $model->school;
                    if (!$org) {
                        $org = $model->kindergarten;
                        return Html::a($org->name, ['/kindergarten/detail', 'id' => $org->id]);
                    } else {
                        return Html::a($org->name, ['/schools/detail', 'id' => $org->id]);
                    }
                }
            ],
            'who',
            [
                'attribute' => 'desc',
                'format' => 'html',
                'value' => function($model) {
                    return '<span class="label label-success">'.$model->yy.'</span>'
                        .'&nbsp;<span class="label label-success">'.$model->qq.'</span>&nbsp;'
                        .$model->desc;
                }
            ],
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
$url_tmpl = Url::to(['/pays/ajax-detail', 'id' => '_id_']);
$this->registerJs($code=<<<EOT
    $(".bn-pay").on("click", function(){
        $("#idModal").modal('show');
        var url = "{$url_tmpl}".replace(/(_id_)/, $(this).data('pay'))
        $("#idModal").find('.modal-body').load(url)
    });
EOT
, View::POS_READY, 'modal');?>
