<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class post extends ActiveRecord
{
    public static function tableName()
    {
        return 'posts'; //Name der Tabelle
    }

    public function rules()
    {
        return [
            [['user_id', 'content'], 'required'],
            ['content', 'string', 'max' => 280], // Beispiel: Maximal 280 Zeichen
        ];
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

}