<?php

use yii\data\ArrayDataProvider;
use yii\base\InvalidConfigException;
use yii\widgets\ActiveForm;
use yii\helpers\Html;


try {
    $this->registerCssFile('@web/css/api.css');
} catch (InvalidConfigException $e) {
}

// Überschrift ausgeben
echo "<h1>Create User</h1>";

$this->title = 'Benutzer erstellen';
$this->params['breadcrumbs'][] = ['label' => 'Benutzer', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="user-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Speichern', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>