<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "school_limit".
 *
 * @property integer $id
 * @property integer $id_parent
 * @property integer $yy
 * @property double $summ
 */
class SchoolLimit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'school_limit';
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
