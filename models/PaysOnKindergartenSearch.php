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
class PaysOnKindergartenSearch extends Model
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

    public function search($idKindg, $params)
    {
        $query = Pays::find();
        $prov = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 40
            ]
        ]);

        $query->andWhere(['id_kindg' => $idKindg]);
        if (!empty($params)) {
            $this->load($params);

            if ($this->year) {
                $query->andWhere(['=', 'yy', $this->year]);
            }
        }

        return $prov;
    }
}
