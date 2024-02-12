<?php
namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\web\UploadedFile;



/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property int $profile_id
 * @property string $profile_about
 * @property string $profile_text
 * @property string $profile_title
 * @property string|null $updated_at
 *
 */

class ProfilePage extends ActiveRecord
{
    public static function tableName()
    {
        return 'profilePage';
    }

    public function rules()
    {
        return [
            [['profile_about', 'profile_text', 'profile_title'], 'required'],
            [['imageFile'], 'file', 'skipOnEmpty'=> true, 'extensions' => 'png, jpg'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'imageFile' => 'Profilbild',
        ];
    }
    public $imageFile;


    public function upload()
    {
        if ($this->validate()) {
            $path = $this->uploadPath() . $this->profile_id . "." . $this->imageFile->extension;
            if ($this->imageFile->saveAs($path)) {
                $this->imageFile = $this->profile_id . "." . $this->imageFile->extension;
                if ($this->save(false)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function uploadPath()
    {
        return 'imagesUpload/';
    }

    public function getProfileImage()
    {
        $basePath = 'imagesUpload/';
        $imagePath = $basePath . ($this->profile_id ?? '');
        $imageFormats = ['jpg', 'png'];

        foreach ($imageFormats as $format)
        {
            $fullImagePath = $imagePath . '.' . $format;
            if(file_exists($fullImagePath))
            {
                return $imagePath . '.' . $format;
            }
        }
        return 'imagesUpload/platzhalter.png';
    }



}