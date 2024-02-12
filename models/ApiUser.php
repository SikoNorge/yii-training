<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property string $user_id
 * @property string $email
 * @property string $password
 *

 */
class ApiUser extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'email', 'password'], 'required'],
            ['email', 'email'],
            ['user_id', 'unique'],
            ['user_id', 'string', 'max' => 32],
            ['email', 'string', 'max' => 254],
            ['password', 'string', 'max' => 128, 'min' => 6],
            [['user_id'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => 'Invalid user ID. Only letters and numbers are allowed.'],
            [['email'], 'match', 'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', 'message' => 'Invalid email address.'],
        ];
    }

    /**
     * {@inheritdoc}
     */

    public static function findUserId($user_id)
    {
        return self::findOne([
            "user_id" => $user_id,
            "user_type" => "user"
        ]);
    }

    public function validatePassword($passwordHash)
    {
        return Yii::$app->getSecurity()->validatePassword($this->password, $passwordHash);
    }



}