<?php
use yii\helpers\Html;
use app\models\School;
?>

<blockquote>
<big><?= Html::a($model['name'], ['/schools/detail', 'id' => $model['id']])?></big>
<?php $school = School::findOne($model['id']);?>
<div>
    <span class="pull-left"><?= $school->boss?></span>
    <span class="pull-right"><?= $school->phone?></span>
</div>
<div class="clearfix"></div>
</blockquote>
