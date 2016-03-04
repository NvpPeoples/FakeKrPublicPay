<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\School;
use app\models\Pays;
use app\models\SchoolSearch;
use app\models\PaysOnSchoolSearch;

class SchoolsController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new SchoolSearch();
        if (Yii::$app->request->get('SchoolSearch', false)===false) {
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
        $school = $this->findModel($id);

        $id = $school->id;
        $stat = Yii::$app->getDb()->cache(function($db) use($id){
            return Pays::find()
                ->distinct()
                ->select([
                    new \yii\db\Expression('yy as year'),
                    new \yii\db\Expression('count(*) as cnt'),
                    new \yii\db\Expression('sum(summ) as summ'),
                ])
                ->andWhere(['id_school' => $id])
                ->groupBy('year')
                ->asArray()
                ->all();
        }, 600);

        return $this->render('detail', [
            'school' => $school,
            'stat'   => $stat,
        ]);
    }

    public function actionPays($ow)
    {
        $school = $this->findModel($ow);

        $searchModel = new PaysOnSchoolSearch();
        if (Yii::$app->request->get('PaysOnSchoolSearch', false)===false) {
            $searchModel->initDefaultValues();
        }
        $prov = $searchModel->search($school->id, Yii::$app->request->queryParams);
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
            'school'     => $school,
            'search'     => $searchModel,
            'years_list' => $years_list,
            'prov'       => $prov
        ]);
    }

    protected function findModel($id)
    {
        if (($model = School::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
