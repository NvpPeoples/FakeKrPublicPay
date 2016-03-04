<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\Kindergarten;
use app\models\Pays;
use app\models\KindergartenSearch;
use app\models\PaysOnKindergartenSearch;

class KindergartensController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new KindergartenSearch();
        if (Yii::$app->request->get('KindergartenSearch', false)===false) {
            $searchModel->initDefaultValues();
        }
        $prov = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'search' => $searchModel,
            'prov'   => $prov
        ]);
    }

    public function actionDetail($id)
    {
        $kindergarten = $this->findModel($id);

        $id = $kindergarten->id;
        $stat = Yii::$app->getDb()->cache(function($db) use($id){
            return Pays::find()
                ->distinct()
                ->select([
                    new \yii\db\Expression('yy as year'),
                    new \yii\db\Expression('count(*) as cnt'),
                    new \yii\db\Expression('sum(summ) as summ'),
                ])
                ->andWhere(['id_kindg' => $id])
                ->groupBy('year')
                ->asArray()
                ->all();
        }, 600);

        return $this->render('detail', [
            'kindergarten' => $kindergarten,
            'stat'   => $stat,
        ]);
    }

    public function actionPays($ow)
    {
        $kindergarten = $this->findModel($ow);

        $searchModel = new PaysOnKindergartenSearch();
        if (Yii::$app->request->get('PaysOnKindergartenSearch', false)===false) {
            $searchModel->initDefaultValues();
        }
        $prov = $searchModel->search($kindergarten->id, Yii::$app->request->queryParams);
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

        return $this->render('pays', [
            'kindergarten' => $kindergarten,
            'search'       => $searchModel,
            'years_list'   => $years_list,
            'prov'         => $prov
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Kindergarten::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
