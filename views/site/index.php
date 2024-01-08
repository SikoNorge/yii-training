<?php
use yii\helpers\Html;
use app\models\ProfilePage;
use app\models\VisitModel;
use app\assets\UserChartAsset;
use yii\bootstrap5\ActiveForm;
use app\assets\AppAsset;


UserChartAsset::register($this);



/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */


$users = $this->context->actionUsersCreatedAt();
$allUser = $this->context->actionAllUser();
$this->title = 'My Yii Application';


$session = Yii::$app->session;
$profileError = $session->getFlash('profileError');
if (isset($profileError)) {
    echo "<div class='alert alert-danger' role='alert'>".$profileError."</div>";
}


$this->registerJs("
        $(document).ready(function() {
        $('#post-form').click(function() {
            // Überprüfen, ob der Benutzer angemeldet ist (hier ein Beispiel, du kannst es an deine Bedürfnisse anpassen)
            var isLoggedIn = " . (Yii::$app->user->isGuest ? 'false' : 'true') . ";
            if (!isLoggedIn) {
                $('#loginErrorModal').modal('show'); // Zeige das Modal an
                // Oder du könntest stattdessen eine benutzerdefinierte Fehlermeldung anzeigen oder andere Aktionen ausführen
            }
        });
    });
        ");


?>
<?php //TODO STYLE IN CSS HINZUFÜGEN ?>
<style>
    .carousel-control-prev-icon {
        background-color: black;
        border-radius: 50%;
    }

    .carousel-control-next-icon {
        background-color: black;
        border-radius: 50%;
    }
    .carousel-control-prev {
        left: -5%; /* Position des linken Pfeils außerhalb des Carousels */
    }

    .carousel-control-next {
        right: -5%; /* Position des rechten Pfeils außerhalb des Carousels */
    }
    #user-carousel {
        position: relative;
        overflow: hidden;
        margin-bottom: 2%;
        box-shadow: 2px 2px 5px 2px rgba(0,0,0,0.5);
    }

    .carousel-inner {
        position: relative;
        padding-bottom: 1%;
        padding-top: 1%;
    }

    .blurry-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("/img/newsletter-1.jpg");
        filter: blur(5px);
        z-index: -1;
    }

</style>

<div class="site-index">
    <h4 class="display-6 text-center">Random User</h4>
    <!-- User display of random user -->
    <div id="user-carousel" class="carousel slide" data-bs-slide="carousel">
    <div class="carousel-inner">
        <div class="blurry-background"></div>
        <?php $itemCount = 0; ?>
        <div class="carousel-item active">
            <div class="row">
            <?php foreach ($allUser as $index => $user) :?>
                <div class="col d-flex flex-column align-items-center">
            <?php
            $profilePage = ProfilePage::find()->where(['id' => $user['id']])->one();
            $basePath = 'imagesUpload/';
            $imagePath = $basePath . ($profilePage ? $profilePage->profile_id : '');

            $imageFormats = ['jpg', 'png']; // Unterstützte Bildformate

            $imageFound = false;
            foreach ($imageFormats as $format) {
                $fullImagePath = $imagePath . '.' . $format;
                if (file_exists($fullImagePath)) {
                    $imageFound = true;
                    $imageExtension = $format;
                    break;
                }
            }

            if ($profilePage && $imageFound) {
                // Bild gefunden, zeige es an
                echo Html::a(
                    Html::img(
                        $imagePath . '.' . $imageExtension,
                        [
                            'class' => 'd-block object-fit-cover',
                            'style' => ' width: 100px; height: 100px; border-radius: 50%; box-shadow: 2px 2px 5px rgba(0,0,0,0.5);'
                        ]
                    ),
                    ['profile/view', 'profile_id' => $profilePage->profile_id]
                );
            } else {
                // Kein Bild gefunden oder Profil nicht vorhanden, zeige ein Platzhalterbild an
                echo Html::a(
                    Html::img(
                        'imagesUpload/platzhalter.png',
                        [
                            'alt' => 'Platzhalterbild',
                            'class' => 'd-block object-fit-cover',
                            'style' => ' width: 100px; height: 100px; border-radius: 50%; box-shadow: 2px 2px 5px rgba(0,0,0,0.5);'
                        ]
                    ),
                    ['profile/view', 'profile_id' => $profilePage->profile_id]
                );
            }

            // Zeige den Namen des Benutzers als Link zur Profilseite
            if ($profilePage) {
                $profileId = $profilePage->profile_id;
                echo Html::a(
                    Html::encode($user['name']),
                    ['profile/view', 'profile_id' => $profileId],
                    ['style' => 'display: block; margin-top: 10px; color: white; text-shadow: 2px 2px 5px rgba(0,0,0,0.5);']
                );
            } else {
                echo Html::encode($user['name']);
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


    <!-- Carousel Controls -->


    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 mb-3">
                <h2>Recent added User</h2>

                <p>
                <ul>
                    <?php foreach ($users as $user) : ?>
                        <li style="display: flex; align-items: center; margin-bottom: 10px;">
                            <?php
                            $profilePage = ProfilePage::find()->where(['id' => $user['id']])->one();
                            $basePath = 'imagesUpload/';
                            $imagePath = $basePath . ($profilePage ? $profilePage->profile_id : '');

                            $imageFormats = ['jpg', 'png']; // Unterstützte Bildformate

                            $imageFound = false;
                            foreach ($imageFormats as $format) {
                                $fullImagePath = $imagePath . '.' . $format;
                                if (file_exists($fullImagePath)) {
                                    $imageFound = true;
                                    $imageExtension = $format;
                                    break;
                                }
                            }

                            if ($profilePage && $imageFound) {
                                // Bild gefunden, zeige es an
                                echo Html::a(
                                 Html::img(
                                    $imagePath . '.' . $imageExtension,
                                    [
                                        'style' => 'max-width: 25px; max-height: 25px; margin-right: 10px; border-radius:50%;'
                                    ]
                                 ),
                                ['profile/view', 'profile_id' => $profilePage->profile_id]
                );
                            } else {
                                // Kein Bild gefunden oder Profil nicht vorhanden, zeige ein Platzhalterbild an
                                echo Html::a(
                                    Html::img(
                                    'imagesUpload/platzhalter.png',
                                    [
                                        'alt' => 'Platzhalterbild',
                                        'style' => 'max-width: 25px; max-height: 25px; margin-right: 10px;'
                                    ]
                                    ),
                                    ['profile/view', 'profile_id' => $profilePage->profile_id]
                                );
                            }

                            // Zeige den Namen des Benutzers als Link zur Profilseite
                            if ($profilePage) {
                                $profileId = $profilePage->profile_id;
                                echo Html::a(
                                    Html::encode($user['name']),
                                    ['profile/view', 'profile_id' => $profileId]
                                );
                            } else {
                                echo Html::encode($user['name']);
                            }
                            ?>

                        </li>
                    <?php endforeach; ?>
                </ul>
                </p>

                <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4 mb-3">
                <h2>Poste etwas</h2>

                <p><?php
                    $form = ActiveForm::begin([
                        'id' => 'post-form',
                        'options' => ['class'=>'form-horizontal'],
                        'enableClientValidation' => true,
                    ]);

                    // Content input feld mit anzeige der zeichenanzahl
                    ?>
                <div style="position:relative;">
                    <?= Html::activeTextarea($postModel, 'content', ['class' => 'form-control', 'rows' => 6, 'maxlength' => 160]) ?>
                    <span id="charCount" style="position:absolute; bottom:0; right:0;"></span>
                </div>
                <p></p>
                    <?php
                    echo Html::submitButton('Post', ['class' => 'btn btn-primary']);

                    ActiveForm::end();
                    ?>
                </p>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const contentField = document.getElementById('<?= Html::getInputId($postModel, 'content') ?>');
                        const charCount = document.getElementById('charCount');

                        contentField.addEventListener('input', function() {
                            charCount.textContent = `${this.value.length}/160`;
                            // Ändere die Hintergrundfarbe, wenn die Hälfte der Zeichen erreicht ist
                            if (this.value.length >= 80) {
                                charCount.style.color = 'darkorange';
                                if (this.value.length >= 140) {
                                    charCount.style.color = 'red';
                            }
                            } else {
                                charCount.style.color = ''; // Zurücksetzen auf Standard-Hintergrundfarbe
                            }
                        });
                    });
                </script>

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
            <!--<div class="col-lg-4 mb-3">
                <h2>Nach Usern suchen</h2>

                <p><?php /* //TODO Suchleiste checken
                    $form = ActiveForm::begin([
                        'method' => 'post',
                        'action' => ['site/index'],
                    ]);

                    echo $form->field($searchModel, 'name')->textInput(['placeholder' => 'Search'])->label(false);
                    echo Html::submitButton('Search', ['class' => 'btn btn-primary']); // Hinzufügen des Submit-Buttons
                    ActiveForm::end();
                    */?>
                </p>

                <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div> !-->
            <div class="col-lg-4">
                <h2>Besuche nach Benutzern</h2>

                <p>
                    <!-- Beispiel-HTML für die Anzeige des Charts -->
                    <canvas id="userVisitsChart" width="400" height="400"></canvas>

                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
                    <?php $this->registerJsFile('userChart.js', ['depends' => [\yii\web\JqueryAsset::class]]); ?>

                </p>

                </p>
            </div>
        </div>
    </div>
        <script>
            document.getElementById('nextButton').addEventListener('click', function () {
                let carousel = new bootstrap.Carousel(document.getElementById('user-carousel'));
                carousel.next();
            });
        </script>
</div>
