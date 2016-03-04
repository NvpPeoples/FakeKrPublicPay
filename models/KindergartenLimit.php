<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kindergarten_limit".
 *
 * @property integer $id
 * @property integer $id_parent
 * @property integer $yy
 * @property double $summ
 */
class KindergartenLimit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kindergarten_limit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_parent', 'yy'], 'integer'],
            [['summ'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_parent' => 'Id Parent',
            'yy' => 'Yy',
            'summ' => 'Summ',
        ];
    }
}
