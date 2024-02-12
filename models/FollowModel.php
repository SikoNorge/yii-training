<?php
namespace app\models;

use yii\db\ActiveRecord;


class FollowModel extends ActiveRecord
{

    public static function tableName()
    {
        return 'follow_table';
    }

    public function rules()
    {
        return [
            [['follower_id', 'following_id'], 'required'],
            [['follower_id', 'following_id'], 'integer'],
        ];
    }

    public static function recordFollow($profile_id, $userId = null)
    {
        // Checkt, ob ein follow bereits besteht
        $existingFollow = self::findOne(['following_id'=>$profile_id, 'follower_id'=> $userId]);

        if (!$existingFollow)
        {
            // Wenn Benutzer (user_id) keinen Eintrag hat, wird er hinzugefügt
            $follow = new FollowModel();
            $follow->following_id = $profile_id;
            $follow->follower_id = $userId;
            $follow->save(false);
        }
    }

    public static function removeFollow($profile_id, $userId)
    {
        $removeFollow = self::find()->where(['following_id'=>$profile_id, 'follower_id'=> $userId])->one()->delete();
    }

    public function getFollowing($userId)
    {
        return self::find()
                ->where(['follower_id'=>$userId])
                ->orderBy(['created_at'=>SORT_DESC])
                ->limit('5')
                ->all();
    }

    public static function getFollowingName($userId)
    {
        $followModel = new FollowModel();
        $followingList = $followModel->getFollowing($userId);
        $profileIds = [];
        foreach ($followingList as $follow) {
            $profileIds[] = $follow->following_id;
        }

        $userName = ProfilePage::find()->where(['profile_id' => $profileIds])->all();


        return  $userName;
    }

    public function getUser()
    {
        return $this->hasMany(Post::className(), ['follower_id' => 'id']);
    }

    public function getProfilePage()
    {
        return $this->hasMany(Post::className(), ['following_id' => 'profile_id']);
    }
}
