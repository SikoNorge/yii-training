<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProfilePage $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="profile-page-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'profile_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'profile_text')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'profile_about')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
