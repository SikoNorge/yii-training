<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use app\models\ProfilePage;


AppAsset::register($this);
$profilePage = ProfilePage::find()->where(['id'=>Yii::$app->user->id])->one();
$this->registerCssFile('@web/css/dark-mode.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js', ['integrity' => 'sha384-pzjw8t+ua/cac9iCtuRbRcG9MI8dD9I2QKkQdy9MnWl3HoMzPz8zW1WYUJlrtJq', 'crossorigin' => 'anonymous']);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

// Form für den Logout Button, um ihn ins Dropdown Menü zu integrieren
$logoutForm = Html::beginForm(['/site/logout'], 'post', ['class' => 'dropdown-item']);
$logoutForm .= Html::submitButton(
    'Logout',
    ['class' => 'btn']
);
$logoutForm .= Html::endForm();

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);

    // Dropdown Menü für den User
    $dropdownItems = [
            [
                'label' => 'Profile page',
                'url' => $profilePage
                    ? ['/profile/view', 'profile_id' => $profilePage->profile_id]
                    : ['/profile/index']
            ],
        ['label' => 'Notification', 'url' => ['/site/about']],
        Yii::$app->user->isGuest
            ? ['label' => 'Login', 'url' => ['/site/login']] // Wenn Gast, zeige Login-Link im Dropdown
            : $logoutForm
    ];

    $dropdownItemsApi = [
            [
                    'label' => 'APIAdmin',
                    'url' => ['/api/admin']
            ],
        [
            'label' => 'APIClient',
            'url' => ['/api/client']
        ]
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            ['label' => 'finApi', 'items' => $dropdownItemsApi],
            Yii::$app->user->isGuest ? ['label' => 'Register', 'url' => ['/site/register']] : "",
            Yii::$app->user->identity &&( Yii::$app->user->identity->user_type === 'admin' || Yii::$app->user->identity->user_type === 'admin_guest') ? ['label' => 'Admin Page', 'url' => ['/admin']] : "",
            Yii::$app->user->isGuest
                ? ['label' => 'Login', 'url' => ['/site/login']]
                : [
                        'label' => Yii::$app->user->identity->name,
                        'items' => $dropdownItems,
            ],

        ]
    ]);


    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; Siko Norge <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
