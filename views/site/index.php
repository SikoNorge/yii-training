<?php

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use app\models\ProfilePage;
use app\models\User;
use app\assets\UserChartAsset;
use yii\bootstrap5\ActiveForm;
use app\assets\AppAsset;
use yii\web\JqueryAsset;

// Register CSS and JS assets
AppAsset::register($this);
UserChartAsset::register($this);

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */

// Get user data
$users = $this->context->getUsersCreatedAt();
$allUser = $this->context->getAllUser();

// Set page title
$this->title = 'My Yii Application';

// Display error message for profile creation
$session = Yii::$app->session;
$profileError = $session->getFlash('profileError');
if (isset($profileError)) {
    echo "<div class='alert alert-danger' role='alert'>".$profileError."</div>";
}

// JavaScript to check user login status before posting
$this->registerJs("
    $(document).ready(function() {
        $('#post-form').click(function() {
            var isLoggedIn = " . (Yii::$app->user->isGuest ? 'false' : 'true') . ";
            if (!isLoggedIn) {
                $('#loginErrorModal').modal('show');
            }
        });
    });
");

//Success modal für ein Post

$postSuccess = Yii::$app->session->getFlash('postSuccess');
if ($postSuccess) {
    $this->registerJs("
        $(document).ready(function() {
            $('#postSuccessModal').modal('show');
        });
    ");
}

?>

<div class="site-index">
    <!-- Random User Carousel -->
    <h4 class="display-6 text-center">Random User</h4>
    <div id="user-carousel" class="carousel slide" data-bs-slide="carousel">
        <div class="carousel-inner">
            <div id="parentContainer">
                <div class="blurry-background" id="blurryBackground"></div>
                <?php $itemCount = 0; ?>
                <div class="carousel-item active">
                    <div class="row">
                        <?php foreach ($allUser as $index => $userData) : ?>
                        <div class="col d-flex flex-column align-items-center">
                            <?php
                            // Display user profile image and name
                            $userPage = $userData->profilePage;
                            if ($userPage) {
                                $profileImage = $userPage->getProfileImage();
                                $profileId = $userPage->profile_id ?? null;
                                echo Html::a(
                                    Html::img(
                                        $profileImage,
                                        [
                                            'class' => 'd-block object-fit-cover',
                                            'style' => 'width: 100px; height: 100px; border-radius: 50%; box-shadow: 2px 2px 5px rgba(0,0,0,0.5);'
                                        ]
                                    ),
                                    ['profile/view', 'profile_id' => $profileId]
                                );
                            }
                            $userName = User::find()->where(['id' => $userData['id']])->one();

                            if ($userPage) {
                                $profileId = $userPage->profile_id;
                                echo Html::a(
                                    Html::encode($userName['name']),
                                    ['profile/view', 'profile_id' => $profileId],
                                    ['style' => 'display: block; margin-top: 10px; color: white; text-shadow: 2px 2px 5px rgba(0,0,0,0.5);']
                                );
                            } else {
                                echo Html::encode($userName['name']);
                            }
                            ?>
                        </div>
                        <?php $itemCount++; ?>
                        <?php if (($index + 1) % 5 === 0) : ?>
                    </div>
                </div>
                <div class="carousel-item<?= $index === 0 ? ' active' : '' ?>">
                    <div class="row">
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#user-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#user-carousel" data-bs-slide="next" id="nextButton">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Body Content -->
    <div class="body-content">
        <div class="row">
            <!-- Recent added User -->
            <div class="col-lg-4 mb-3">
                <h2>Recent added User</h2>
                <p>
                <ul>
                    <?php foreach ($users as $user) : ?>
                        <li style="display: flex; align-items: center; margin-bottom: 10px;">
                            <?php
                            // Display recent added user with profile image and name
                            $profilePage = ProfilePage::find()->where(['id' => $user['id']])->one();

                            if ($profilePage) {
                                $profileImage = $profilePage->getProfileImage();
                                $profileId = $profilePage->profile_id ?? null;
                                echo Html::a(
                                    Html::img(
                                        $profileImage,
                                        [
                                            'style' => 'max-width: 25px; max-height: 25px; margin-right: 10px; border-radius:50%;'
                                        ]
                                    ),
                                    ['profile/view', 'profile_id' => $profileId]
                                );
                            }

                            if ($profilePage) {
                                echo Html::a(
                                    Html::encode($user['name']),
                                    ['profile/view', 'profile_id' => $profileId],
                                    ['style' => 'color: #58f2a9']
                                );
                            } else {
                                echo Html::encode($user['name']);
                            }
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                </p>
            </div>

            <!-- Post something -->
            <div class="col-lg-4 mb-3">
                <h2>Poste etwas</h2>
                <p>
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'post-form',
                        'options' => ['class'=>'form-horizontal'],
                        'enableClientValidation' => true,
                    ]);
                    ?>
                </p>
                <div style="position:relative;">
                    <?= $form->field($postModel, 'content')->textarea([
                        'class' => 'form-control',
                        'style' => 'background: #3f3f3f; color: #ccc',
                        'rows' => 6,
                        'maxlength' => 160
                    ]) ?>
                    <span id="charCount" style="position:absolute; bottom:0; right:0;"></span>
                </div>
                <p>
                    <?php
                    echo Html::submitButton('Post', ['class' => 'btn btn-primary', 'style' => 'background: #48cf90']);
                    ActiveForm::end();
                    ?>
                </p>

                <!-- JavaScript for character count and login error modal -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const contentField = document.getElementById('<?= Html::getInputId($postModel, 'content') ?>');
                        const charCount = document.getElementById('charCount');

                        contentField.addEventListener('input', function() {
                            charCount.textContent = `${this.value.length}/160`;
                            if (this.value.length >= 80) {
                                charCount.style.color = 'darkorange';
                                if (this.value.length >= 140) {
                                    charCount.style.color = 'red';
                                }
                            } else {
                                charCount.style.color = '';
                            }
                        });
                    });
                </script>

                <!-- Login Error Modal -->
                <div class="modal fade" id="loginErrorModal" tabindex="-1" aria-labelledby="loginErrorModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="loginErrorModalLabel">Fehlermeldung</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Du musst angemeldet sein, um Posts zu erstellen!
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Post Success Modal -->
            <div class="modal fade" id="postSuccessModal" tabindex="-1" aria-labelledby="postSuccessModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #3f3f3f; color: #ccc">
                            <h5 class="modal-title" id="postSuccessModalLabel">Erfolgreich erstellt</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="background-color: #3f3f3f; color: #ccc">
                            Ihr Post wurde erfolgreich erstellt.
                        </div>
                        <div class="modal-footer" style="background-color: #3f3f3f; color: #ccc">
                            <button type="button" class="btn" style="background: #58f2a9" data-bs-dismiss="modal">Schließen</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Visits Chart -->
            <div class="col-lg-4">
                <h2>Besuche nach Benutzern</h2>
                <p>
                    <!-- Example HTML to display the chart -->
                    <canvas id="userVisitsChart" width="400" height="400"></canvas>

                    <!-- Include Chart.js library -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
                    <?php try {
                        $this->registerJsFile('userChart.js', ['depends' => [JqueryAsset::class]]);
                    } catch (InvalidConfigException $e) {
                    } ?>
                </p>
            </div>
        </div>
    </div>
</div>
