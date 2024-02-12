<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProfilePage $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="profile-page-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], ]); ?>

    <?= $form->field($model, 'profile_title')->textInput(['maxlength' => true, 'style' => 'background: #3f3f3f; color: #ccc']) ?>

    <?= $form->field($model, 'profile_text')->textarea(['maxlength' => true, 'style' => 'background: #3f3f3f; color: #ccc']) ?>

    <?= $form->field($model, 'profile_about')->textInput(['maxlength' => true, 'style' => 'background: #3f3f3f; color: #ccc']) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
