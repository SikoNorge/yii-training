<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $created_at
 * @property string|null $updated_at
 *

 */
class RegisterForm extends ActiveRecord implements IdentityInterface

{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
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
    public $password_confirm;
    public function rules()
    {
        return [
            [['name', 'email', 'password', 'password_confirm'], 'required'],
            ['password', 'compare', 'compareAttribute' => 'password_confirm', 'message'=>'Password Confirm does not match'],
            ['email','email', 'message' => 'Not a valid Email'],
            ['email', 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'password'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return[
            'id' => 'ID',
            'name'=>'Name',
            'email'=>'Email',
            'password'=>'Password'
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token'=>$token]);
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
            'email' => $email,
            'user_type' => 'user'
        ]);
    }
        public function validatePassword($passwordHash)
    {
        return Yii::$app->getSecurity()->validatePassword($this->password, $passwordHash);
    }

    public function login()
    {
        $user = $this->findByUserEmail($this->email);
        if ($user && $this->validatePassword($this->password))
        {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        } else
        {
            $this->addError('password', 'Incorrect username or password');
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $profile = new ProfilePage();
            $profile->id = $this->id;
            $profile->profile_about = 'Mein Name ist '.$this->name;
            $profile->profile_text = 'Ich mag Züge';
            $profile->profile_title = 'Das ist meine Profil Seite';

            $profile->save(false);
        }
    }

}