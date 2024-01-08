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
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $user_type
 * @property string|null $created_at
 * @property string|null $updated_at
 *

 */
class User extends ActiveRecord implements IdentityInterface
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public $rememberMe = true;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password', ], 'required'],
            [['user_type'], 'safe'],
            ['email', 'email'],
            ['email', 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'password'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($auth_key)
    {
        return $this->auth_key === $auth_key;
    }

    public static function findByUserEmail($email)
    {
        return self::findOne([
            "email" => $email,
            "user_type" => "user"
        ]);
    }

    public function validatePassword($passwordHash)
    {
        return Yii::$app->getSecurity()->validatePassword($this->password, $passwordHash);
    }

    public static function findByAdminEmail($email)
    {
        return self::findOne([
            "email" => $email,
            "user_type" => "admin"
        ]);
    }

    public function login()
    {
        $user = $this->findByUserEmail($this->email);
        if ($user && $this->validatePassword($user->password))
        {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }else
        {
            $user = $this->findByAdminEmail($this->email);
            if ($user && $this->validatePassword($user->password))
            {
                return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
            }else
            {
                $this->addError('password', 'Incorrect username or password.');
            }
        }
    }

    public function adminLogin()
    {
        $user = $this->findByAdminEmail($this->email);
        if ($user && $this->validatePassword($user->password))
        {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }else
        {
            $this->addError('password', 'Incorrect username or password.');
        }
    }

    // Verbindungen zwischen den Tabellen
    public function getProfilePage()
    {
        return $this->hasOne(ProfilePage::className(), ['id'=>'id']);
    }

    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['user_id' => 'id']);
    }

}