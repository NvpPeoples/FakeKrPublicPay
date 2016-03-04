<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Region".
 *
 * @property integer $code_reg
 * @property string $name_reg
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_reg'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code_reg' => 'Code Reg',
            'name_reg' => 'Name Reg',
        ];
    }

    public function getSchools()
    {
        return $this->hasMany(School::className(), ['code_reg' => 'code_reg']);
    }

    public function getKindergartens()
    {
        return $this->hasMany(Kindergarten::className(), ['code_reg' => 'code_reg']);
    }
}
