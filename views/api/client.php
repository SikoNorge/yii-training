<?php

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\assets\AppAsset;
use yii\web\JqueryAsset;

$this->registerCssFile('@web/css/api.css');

?>

<div class="container">
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <?= Html::a('Banks', ['/api/banks'], ['class' => 'card-link', 'style' => 'font-size: 22px;']) ?>
                    <div class="overlay">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/api/banks']) ?>"
                           class="stretched-link"></a>
                    </div>
                    <ul class="text-left mt-3" style="color: black; text-align: left;">
                        <li>Select a bank</li>
                        <li>Get all Information of it</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <?= Html::a('TODO', ['/site/about'], ['class' => 'card-link']) ?>
                    <ul class="text-left mt-3" style="color: black; text-align: left;">
                        <li>Punkt 1</li>
                        <li>Punkt 2</li>
                        <li>Punkt 3</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <?= Html::a('TODO', ['/site/about'], ['class' => 'card-link']) ?>
                    </div>
                    <ul class="text-left mt-3" style="color: black; text-align: left;">
                        <li>Punkt 1</li>
                        <li>Punkt 2</li>
                        <li>Punkt 3</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <?= Html::a('TODO', ['/site/about'], ['class' => 'card-link']) ?>
                    <ul class="text-left mt-3" style="color: black; text-align: left;">
                        <li>Punkt 1</li>
                        <li>Punkt 2</li>
                        <li>Punkt 3</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <?= Html::a('TODO', ['/site/about'], ['class' => 'card-link']) ?>
                    <ul class="text-left mt-3" style="color: black; text-align: left;">
                        <li>Punkt 1</li>
                        <li>Punkt 2</li>
                        <li>Punkt 3</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Weitere Karten hier einfügen -->
</div>

