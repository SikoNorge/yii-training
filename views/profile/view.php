<?php

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;


/** @var yii\web\View $this */
/** @var app\models\ProfilePage $model */

// Fügt eine CSS Datei hinzu
try {
    $this->registerCssFile('@web/css/post.css');
} catch (InvalidConfigException $e) {
}
try {
    $this->registerCssFile('@web/css/dark-mode.css');
} catch (InvalidConfigException $e) {
}


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
?>


<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="profile-top">
        <div class="profile-section">
            <div style="margin-right: 5px;">
                <?php if (isset($imageExtension)) : ?>
                    <!-- Bild gefunden, zeige es an -->
                    <?= Html::img(
                        $imagePath . $imageExtension,
                        [
                            'alt' => 'Profilbild',
                            'style' => 'position: relative; max-width: 200px; max-height: 200px; border-radius: 50%; z-index: 2;'
                        ]
                    ) ?>
                <?php else : ?>
                    <!-- Falls kein Bild gefunden wurde, zeige ein Platzhalter Bild oder eine alternative Nachricht an -->
                    <?= Html::img(
                        'imagesUpload/platzhalter.png',
                        [
                            'alt' => 'Platzhalter',
                            'style' => 'position: relative; max-width: 200px; max-height: 200px; border-radius: 50%; z-index: 2;'
                        ]
                    ) ?>
                <?php endif; ?>
            </div>
            <div class="profile-section-box">
                    <div class="profile-section-name">
                        <a><?= $userName ?></a>
                    </div>
                    <div class="profile-section-about">
                        <p><?= $model->profile_about ?></p>
                    </div>
                    <div class="profile-section-text">
                        <p><?= $model->profile_text ?></p>
                    </div>
                <div class="profile-section-info">
                    <a>Anzahl der Besuche: <?= $visitCount ?></a>
                    <a>Last Updated
                        <?= Html::encode(date('d.m.Y', strtotime($model->updated_at)))?></a>
                </div>
            </div>

        </div>
        <div id="update">
            <?php
            if(!Yii::$app->user->isGuest) {
                if (Yii::$app->user->identity->id == $model->id) {
                    echo Html::a('Update', ['update', 'profile_id' => $model->profile_id], ['class' => 'btn']);
                }
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 text-center">
            <h4>Following: <?= count($followingName)?></h4>
            <div class="<?= $followingName ? 'following-content' : '' ?>">
                <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id !== $model->id): ?>
                    <?php
                    // Der 'profile_id'-Parameter wird aus diesem Modell extrahiert
                    $checkFollow = $checkFollow ?? null;
                    $form = ActiveForm::begin([
                        'action' => ['profile/follow', 'profile_id' => $model->profile_id],
                        'options' => [
                            'class' => 'form-inline',
                        ],
                    ]);
                    ?>
                    <?= Html::hiddenInput('follower_id', Yii::$app->user->id) ?>
                    <?= Html::hiddenInput('following_id', $model->id) ?>
                    <?= Html::submitButton(
                        $checkFollow ? 'Nicht mehr folgen' : 'Folgen',
                        [
                            'class' => 'btn ' . ($checkFollow ? 'btn-danger' : 'btn-success')
                        ]
                    ) ?>

                    <?php ActiveForm::end(); ?>
                <?php endif; ?>
                <?php if ($followingName): ?>
                <?php foreach ($followingName as $index => $following): ?>
                    <?php $profile_info = $following->profilePage ?>
                <div class="following-box">
                        <p><?= $following->name;?></p>
                    <div class="following">
                        <?php if ($profile_info->profile_id) {
                            $profileImage = $profile_info->getProfileImage();
                            $profileId = $profile_info->profile_id ?? null;

                            echo Html::a(
                                Html::img(
                                    $profileImage,
                                    [
                                        'class' => 'd-block object-fit-cover',
                                    ]
                                ),
                                ['profile/view', 'profile_id' => $profileId]
                            );
                        }?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6 custom-posts">
            <div class="posts-box" id="posts-box">
                <h4 class="text-center">Posts</h4>
                <?php foreach ($userPosts as $post): ?>
                    <div class="post-box">
                        <div class="post">
                            <p><?= $post->content ?></p>
                            <div class="post-date">
                                <?= date('d.m.Y', strtotime($post->created_at))?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div id="loadMoreButton" style="display: block; color: #58f2a9">
                    Load More Posts
                </div>
                    <?php $url = Yii::$app->urlManager->createUrl(['profile/load-posts']); ?>
                    <?php try {
                        $this->registerJsFile('loadPost.js', [
                            'depends' => [JqueryAsset::class],
                            'id' => 'loadPostScript',
                            'data' => ['url' => $url]
                        ]);
                    } catch (InvalidConfigException $e) {
                    } ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="right-content">
                <h4 class="text-center">Something</h4>
                    <i class="bi bi-heart"></i>
             </div>


        </div>
    </div>
</div>