<?php

use app\models\ProfilePage;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Profile Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name'=>'user.name',
            'profile_about',
            //'profile_text',
            //'profile_title',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'template' => '{view}',
                'urlCreator' => function ($action, ProfilePage $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'profile_id' => $model->profile_id]);
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
