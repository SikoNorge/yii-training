<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Banks';

echo $accessToken;
?>

<h1>Select a Bank</h1>

<?php $form = ActiveForm::begin()?>

    <?= $form->field($model, 'bankId')->dropDownList(
            array_combine($bankIds, $bankNames),
            ['prompt' => 'Bitte Bank auswählen']
    ) ?>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php if ($bankInfo): ?>
    <h2>Bank Details</h2>
    <ul>
        <li>ID: <?= $bankInfo['id'] ?></li>
        <li>Name: <?= $bankInfo['name'] ?></li>
        <?php if (isset($bankInfo['bic'])): ?>
            <li>BIC: <?= $bankInfo['bic']; ?></li>
        <?php endif; ?>
        <li>BLZ: <?= $bankInfo['blz'] ?></li>
        <li>Location: <?= $bankInfo['location'] ?>
            <?php if (isset($bankInfo['city'])): ?>
        <li>City: <?= $bankInfo['city']; ?></li>
    <?php endif; ?>
    </ul>
<?php endif; ?>