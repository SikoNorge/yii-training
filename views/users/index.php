<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style' => 'color: white;'], // Dies ändert die Farbe des gesamten GridViews
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // Hier kommen die restlichen Spaltendefinitionen
            [
                'attribute' => 'id',
                'contentOptions' => ['style' => 'color: white;'], // Ändere die Farbe dieser speziellen Spalte
            ],
            [
                'attribute' => 'name',
                'contentOptions' => ['style' => 'color: white;'], // Ändere die Farbe dieser speziellen Spalte
            ],
            [
                'attribute' => 'email',
                'contentOptions' => ['style' => 'color: white;'], // Ändere die Farbe dieser speziellen Spalte
            ],
            [
                'attribute' => 'user_type',
                'contentOptions' => ['style' => 'color: white;'], // Ändere die Farbe dieser speziellen Spalte
            ],

            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, User $model) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'contentOptions' => ['style' => 'color: white;'], // Ändere die Farbe dieser speziellen Spalte
            ],
        ],
    ]); ?>



</div>
