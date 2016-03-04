<?php
use yii\helpers\Html;
use app\models\Kindergarten;
?>

<blockquote>
<big>
    <?= Html::a($model['name'], ['/kindergartens/detail', 'id' => $model['id']])?>
</big>
<?php $kind = Kindergarten::findOne($model['id']);?>
<div>
    <span class="pull-left"><?= $kind->boss?></span>
    <span class="pull-right"><?= $kind->phone?></span>
</div>
<div class="clearfix"></div>
</blockquote>
