<?php
use yii\helpers\Html;

$this->registerCssFile('@web/css/api.css');


if (Yii::$app->session->hasFlash('error')) {
    echo '<div class="alert alert-danger">' . Yii::$app->session->getFlash('error') . '</div>';
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <?= Html::a('Create User', ['/api/create_user'], ['class' => 'card-link', 'style' => 'font-size: 22px;']) ?>
                    <div class="overlay">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/api/create_user']) ?>" class="stretched-link"></a>
                    </div>
                    <ul class="text-left mt-3" style="color: black; text-align: left;">
                        <li>User_id</li>
                        <li>password</li>
                        <li>email</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <?= Html::a('User Liste', ['/api/admin_list_user'], ['class' => 'card-link', 'style' => 'font-size: 22px;']) ?>
                    <div class="overlay">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/api/admin_list_user']) ?>" class="stretched-link"></a>
                    </div>
                    <ul class="text-left mt-3" style="color: black; text-align: left;">
                        <li>User Liste:</li>
                        <li>UserId</li>
                        <li>Registrierungsdatum</li>
                        <li>Anzahl der Bankverbindungen</li>
                        <li>User Access Token</li>
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

