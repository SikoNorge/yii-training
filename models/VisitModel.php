<?php
 namespace app\models;

 use yii\db\ActiveRecord;
use app\models\User;


 /**
  * This is the model class for table "visits".
  *
  * @property int $id
  * @property int $profile_id
  * @property string $user_id
  * @property string $visit_time
  *

  */

 class VisitModel extends ActiveRecord
 {

     public static function tableName()
     {
         return 'visits';
     }
     public static function recordVisit($profile_id, $userId = null)
     {
         // Überprüft, ob der Benutzer bereits einen Eintrag hat
         $existingVisit = self::findOne(['profile_id'=>$profile_id, 'user_id'=> $userId]);

         if (!$existingVisit) {
             // Wenn Benutzer (user_id) keinen Eintrag hat, wird er hinzugefügt
             $visit = new VisitModel();
             $visit->profile_id = $profile_id;
             $visit->user_id = $userId;
             $visit->save();
         }
     }

     public static function getUserVisitCount()
     {
         // Query, um dei Anzahl der Besucher pro User zu zählen
         $userVisits = static::find()
             ->select(['profile_id', 'COUNT(*) AS visit_count'])
             ->groupBy(['profile_id'])
             ->orderBy(['visit_count' => SORT_DESC])
             ->limit(5)
             ->asArray()
             ->all();

         // Array erstellen für den User Chart
         $labels = [];
         $visits = [];

         foreach ($userVisits as $visit)
         {
             $profilePage = ProfilePage::findOne(['profile_id'=>$visit['profile_id']]);
             if($profilePage){
                $user = $profilePage->user;
                if($user)
                {
                    $userName = $user->name;
                }
             }else {
                 $userName = $visit['profile_id'];
             }
             $labels [] = $userName;
             $visits [] = $visit['visit_count'];
         }
         return [
             'labels' => $labels,
             'visits' => $visits,
         ];
     }

     public function getUser()
     {
         // Definiere die Beziehung zwischen VisitModel und User
         return $this->hasOne(User::class, ['id' => 'user_id']);
     }

 }