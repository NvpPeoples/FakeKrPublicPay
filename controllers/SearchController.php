<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\models\Pays;
use app\models\GlobSearch;
use yii\data\ArrayDataProvider;

class SearchController extends Controller
{
    public function actionIndex($keyword=null)
    {
        $searchModel = new GlobSearch();
        if (!is_null($keyword)) {
            $searchModel->keyword = trim($keyword);
        } else {
            $searchModel->load(Yii::$app->request->queryParams);
        }
        if (Yii::$app->request->get('GlobSearch', false)===false) {
            $searchModel->initDefaultValues();
        }

        $searchModel->keyword = trim($searchModel->keyword);
        if (!strlen($searchModel->keyword)) {
            $prov = new ArrayDataProvider();
        } else {
            $query1 = (new \yii\sphinx\Query)
                ->select(['*'])
                ->from('pays')
                ->match($searchModel->keyword);
            $data = $query1
                ->limit(400);

            $prov = new ArrayDataProvider([
                'allModels' => $data->all(),
                'pagination' => [
                    'pageSize' => 40,
                ],
            ]);
        }

        return $this->render('search',[
            'search' => $searchModel,
            'prov'   => $prov,
        ]);
        //echo \yii\helpers\VarDumper::dumpAsString(var_dump($ids));
    }
}
