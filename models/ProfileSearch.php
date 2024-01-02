<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProfilePage;
use app\models\User;

/**
 * ProfileSearch represents the model behind the search form of `app\models\ProfilePage`.
 */
class ProfileSearch extends ProfilePage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profile_id', 'id'], 'integer'],
            [['profile_about', 'profile_text', 'profile_title', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($name)
    {

        $userId = User::find()
            ->select('id')
            ->where(['name' => $name])
            ->scalar();
        $dataProvider = null;
        if ($userId !== null) {
            $query = ProfilePage::find()
                ->where(['id'=>$userId]) //verbindung zu User-modell
                ->all();
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

        }
        return $dataProvider;
    }




}
