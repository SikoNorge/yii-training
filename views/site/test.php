<?php
$message= ($_SERVER['REMOTE_ADDR']);
use yii\helpers\Html;?>
<?= Html::encode($message) ?>