<?php
use yii\helpers\Html;
use app\models\Pays;
?>

<blockquote>
<big>
    <?= Html::a($model['who'], ['/pays/link', 'id' => $model['id']])?>
</big>
<?php $pay = Pays::findOne($model['id']);?>
<div class="row">
    <div class="col-sm-10"><?= $pay->desc?></div>
    <div class="col-sm-2">
        <span class="pull-right"><?= Yii::$app->formatter->asCurrency($pay->summ)?></span>
    </div>
</div>
<div class="clearfix"></div>
</blockquote>
