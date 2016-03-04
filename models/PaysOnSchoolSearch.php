<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pays;


/**
 * This is the model class
 *
 * @property string $year
 */
class PaysOnSchoolSearch extends Model
{
    public $year;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'year'  => 'Рік',
        ];
    }

    public function initDefaultValues()
    {
        $this->year = date('Y');
    }

    public function search($idSchool, $params)
    {
        $query = Pays::find();
        $prov = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 40
            ]
        ]);

        $query->andWhere(['id_school' => $idSchool]);
        if (!empty($params)) {
            $this->load($params);

            if ($this->year) {
                $query->andWhere(['=', 'yy', $this->year]);
            }
        }

        return $prov;
    }
}
