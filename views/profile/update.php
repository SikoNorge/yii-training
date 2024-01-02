<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ProfilePage $model */

$this->title = 'Update Profile Page: ' . Yii::$app->user->identity->name;
$this->params['breadcrumbs'][] = ['label' => 'Profile Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->profile_id, 'url' => ['view', 'profile_id' => $model->profile_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="profile-page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
