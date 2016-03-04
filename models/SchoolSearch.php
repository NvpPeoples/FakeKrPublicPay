<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\School;


/**
 * This is the model class for table "School".
 *
 * @property integer $id
 * @property string $name
 * @property string $boss
 * @property integer $code_reg
 * @property string $phone
 */
class SchoolSearch extends Model
{
    public $keyword;
    public $region;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword'], 'string'],
            [['region'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kyeword' => 'Ключове слово',
            'region'  => 'Район міста',
        ];
    }

    public function initDefaultValues()
    {
        $this->keyword = '';
        $this->region  = '';
    }

    public function search($params)
    {
        $query = School::find();
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
                    ['like', 'name', $this->keyword],
                    ['like', 'boss', $this->keyword]
                ]);
            }
            if ($this->region) {
                $query->andWhere(['=', 'code_reg', $this->region]);
            }
        }

        return $prov;
    }
}
