<?php
namespace app\models;

use Yii;
use app\models\School;

/**
 * This is the model class for table "Pays".
 *
 * @property integer $id
 * @property integer $id_school
 * @property integer $id_kindg
 * @property integer $yy
 * @property integer $qq
 * @property string $who
 * @property string $desc
 * @property double $summ
 */
class Pays extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Pays';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_school', 'id_kindg'], 'integer'],
            [['yy', 'qq'], 'integer'],
            [['who', 'desc'], 'string'],
            [['summ'], 'number'],
        ];
    }

    public function getSchool()
    {
        return $this->hasOne(School::className(), ['id' => 'id_school']);
    }

    public function getKindergarten()
    {
        return $this->hasOne(Kindergarten::className(), ['id' => 'id_kindg']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_school' => 'Id School',
            'id_kindg' => 'Id Kindg',
            'yy' => 'Рік',
            'qq' => 'Квартал',
            'who' => 'Отримувач',
            'desc' => 'Призначення',
            'summ' => 'Сума',
        ];
    }
}
