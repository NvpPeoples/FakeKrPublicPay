<?php

use yii\helpers\Html;
use app\models\School;
use app\models\Kindergarten;

$this->title = 'Всі платежі';
$this->params['breadcrumbs'][] = [
    'label' => 'Платежі',
    'url'   => ['index']
];
?>

<h1>Платіж №<?= $model->id?></h1>
<div class="pay-detail lead">
    <dl>
        <dt class="text-muted">Замовник</dt>
        <dd><?= Html::a($owner->name, 
            ($owner instanceof School ? ['/schools/detail', 'id' => $owner->id] : ['/kindergartens/detail', 'id' => $owner->id])
        )?></dd>
        <dt class="text-muted"><br/>Призначення платежу</dt>
        <dd><?= $model->desc?></dd>
    </dl>
    <div class="row-fluid">
        <div class="col-sm-6">
            <dl>
                <dt class="text-muted">Час операції</dt>
                <dd><?= $model->yy?>рік, <?= $model->qq?> квартал</dd>
                <dt class="text-muted"><br/>Сума</dt>
                <dd><big><?= Yii::$app->formatter->asCurrency($model->summ)?></big></dd>
            </dl>
        </div>
        <div class="col-sm-6">
            <dl>
                <dt class="text-muted">Отримувач</dt>
                <dd><?= Html::encode($model->who)?></dd>
            </dl>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
