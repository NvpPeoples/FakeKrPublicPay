<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron" style="margin:0">
        <h1>Прозорі фінанси навчальних закладів!</h1>

        <p class="lead">Ви маєте змогу відслідковувати платежі, що здійснюють навчальні заклади у своїй діяльності.</p>
    </div>

    <div class="global-search row lead">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['/search'],
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
        ]);?>
            <div class="input-group col-sm-12">
                <?= Html::input('text', 'keyword', '', [
                    'class' => 'form-control search-query input-lg',
                    'placeholder' => 'пошук платежу, ...'
                ]);?>
                <span class="input-group-btn">
                    <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span> знайти на сайті', [
                        'class' => 'btn btn-primary btn-lg'
                    ]);?>
                </span>
            </div>
        <?php ActiveForm::end(); ?>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 bg-success">
                <h2>ШКОЛИ</h2>

                <div class="text-center" style="font-size:450%"><?= app\models\School::find()->count()?></div>
                <br/>
                <p class="text-center"><?= Html::a('всі школи <i class="glyphicon glyphicon-chevron-right"></i>', ['/schools'], [
                        'class' => 'btn btn-default'
                    ]);?></p>
            </div>
            <div class="col-lg-4 bg-warning">
                <h2>ДИТСАДКИ</h2>
                <div class="text-center" style="font-size:450%">160</div>
                <br/>

                <p class="text-center"><?= Html::a('всі садочки <i class="glyphicon glyphicon-chevron-right"></i>', ['/kindergartens'], [
                        'class' => 'btn btn-default'
                    ]);?></p>
            </div>
            <div class="col-lg-4 bg-info">
                <h2>ПЛАТЕЖІ</h2>

                <div class="text-center" style="font-size:450%"><?= app\models\Pays::find()->count()?></div>
                <br/>
                <p class="text-center"><?= Html::a('всі платежі <i class="glyphicon glyphicon-chevron-right"></i>', ['/pays'], [
                        'class' => 'btn btn-default'
                    ]);?></p>
            </div>
        </div>
    </div>
</div>
