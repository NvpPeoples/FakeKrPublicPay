<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\models\Pays;
use app\models\PaysSearch;

class PaysController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new PaysSearch();
        if (Yii::$app->request->get('PaysSearch', false)===false) {
            $searchModel->initDefaultValues();
        }
        $prov = $searchModel->search(Yii::$app->request->queryParams);

        $years_list = Yii::$app->getDb()->cache(function($db){
            return ArrayHelper::map(
                Pays::find()
                    ->distinct()
                    ->select(['yy as id', new \yii\db\Expression('yy||" рік" as title')])
                    ->orderBy('id desc')
                    ->asArray()
                    ->all(),
                'id', 'title');
        }, 600);

        return $this->render('index', [
            'search'     => $searchModel,
            'prov'       => $prov,
            'years_list' => $years_list,
        ]);
        return $this->render('index');
    }

    public function actionLink($id)
    {
        $model = $this->findModel($id);
        if ($owner = $model->school){
        } else {
            $owner = $model->kindergarten;
        }

        return $this->render('detail', [
            'model' => $model,
            'owner' => $owner,
        ]);
    }

    public function actionAjaxDetail($id, $t=null)
    {
        $model = $this->findModel($id);
            
        if ($t == 's') {
            $owner = $model->school;
        } else if ($t == 'k'){
            $owner = $model->kindergarten;
        } else {
            if ($owner = $model->school){
            } else {
                $owner = $model->kindergarten;
            }
        }

        return $this->renderAjax('ajax-detail', [
            'model' => $model,
            'owner' => $owner,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Pays::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
