<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ProfilePage;

/** @var yii\web\View $this */
/** @var app\models\ProfilePage $model */

$basePath = 'imagesUpload/';
$imagePath = $basePath . $model->profile_id;
// Pfade zu möglichen Bildformaten
$imageFormats = ['.jpg', '.png'];
//Sucht nacht der richtigen Endung des Bildes
foreach ($imageFormats as $format) {
    $fullImagePath = $imagePath . $format;
    if (file_exists($fullImagePath)) {
        $imageExtension = $format;
        break;
    }
}
$this->title = $model->user->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    if(!Yii::$app->user->isGuest) {
        if (Yii::$app->user->identity->id == $model->id) {
            echo Html::a('Update', ['update', 'profile_id' => $model->profile_id], ['class' => 'btn btn-primary']);
        }
    }
    ?>
    <p></p>
    <div style="display: flex;">
        <div style="margin-right: 5px;">
            <?php if (isset($imageExtension)) : ?>
                <!-- Bild gefunden, zeige es an -->
                <?= Html::img(
                    $imagePath . $imageExtension,
                    [
                        'alt' => 'Profilbild',
                        'style' => 'max-width: 200px; max-height: 200px; border-radius: 50%;'
                    ]
                ) ?>
            <?php else : ?>
                <!-- Falls kein Bild gefunden wurde, zeige ein Platzhalterbild oder eine alternative Nachricht an -->
                <?= Html::img(
                    'imagesUpload/platzhalter.png',
                    [
                        'alt' => 'Platzhalter',
                        'style' => 'max-width: 200px; max-height: 200px; border-radius: 50%;'
                    ]
                ) ?>
            <?php endif; ?>
        </div>

        <div>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'profile_title',
                    'profile_about',
                    'profile_text',
                ],
            ]) ?>
        </div>
    </div>
    <p>Anzahl der Besuche: <?= $visitCount ?></p>
    <div>
        <a>Last Updated
        <?= Html::encode(date('d.m.Y', strtotime($model->updated_at)))?>
        </a>
    </div>


</div>
