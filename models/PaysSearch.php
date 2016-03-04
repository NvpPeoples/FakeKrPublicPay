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
class PaysSearch extends Model
{
    public $year;
    public $keyword;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year'], 'integer'],
            [['keyword'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kyeword' => 'Ключове слово',
            'year'  => 'Рік',
        ];
    }

    public function initDefaultValues()
    {
        $this->year = date('Y');
    }

    public function search($params)
    {
        $query = Pays::find();
        $prov = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 40
            ]
        ]);

        if (!empty($params)) {
            $this->load($params);

            if (strlen($this->keyword)) {
                $query->andWhere([
                    'or',
                    ['like', 'who', $this->keyword],
                    ['like', 'desc', $this->keyword],
                ]);
            }

            if ($this->year) {
                $query->andWhere(['=', 'yy', $this->year]);
            }
        }

        return $prov;
    }
}
