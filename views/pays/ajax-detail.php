<?php

use yii\helpers\Html;
?>

<div class="pay-detail lead">
    <dl>
        <dt class="text-muted">Замовник</dt>
        <dd><?= Html::encode($owner->name)?></dd>
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
        <div class="col-sm-6">
            <br/>
            <?= Html::a('<i class="glyphicon glyphicon-link"></i> постійне посилання', ['link', 'id' => $model->id], [
                'class' => 'btn btn-default pull-right'
            ]);?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
