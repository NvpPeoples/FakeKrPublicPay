<?php

namespace app\models;
use \app\models\SchoolLimit;

use Yii;

/**
 * This is the model class for table "School".
 *
 * @property integer $id
 * @property string $name
 * @property string $boss
 * @property integer $code_reg
 * @property string $phone
 */
class School extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'School';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'boss', 'phone'], 'string'],
            [['code_reg'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назва школи',
            'boss' => 'Керівник',
            'code_reg' => 'Район міста',
            'phone' => 'Телефон',
        ];
    }

    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['code_reg' => 'code_reg']);
    }

    public function getLimitOfYear($y)
    {
        return SchoolLimit::find()
            ->select('summ')
            ->where(['id_parent' => $this->id, 'yy' => $y])
            ->scalar();
    }

    public function getCntOfPays()
    {
        return Pays::find()->where(['id_school' => $this->id])->count();
    }

    public function getCntOfCurYearPays()
    {
        return Pays::find()->where(['id_school' => $this->id, 'yy' => date('Y')])->count();
    }

    public function getSumOfCurYearPays()
    {
        return (float)Pays::find()->where(['id_school' => $this->id, 'yy' => date('Y')])->select('sum(summ)')->scalar();
    }
}
