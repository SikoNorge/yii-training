<?php
use yii\helpers\Html;
use app\models\ProfilePage;
use app\models\VisitModel;
use app\assets\UserChartAsset;
use yii\bootstrap5\ActiveForm;


UserChartAsset::register($this);

/** @var yii\web\View $this */
/** @var app\models\ProfileSearch $searchModel */
/** @var yii\widgets\ActiveForm $form */


$users = $this->context->actionUsersCreatedAt();
$this->title = 'My Yii Application';


$session = Yii::$app->session;
$profileError = $session->getFlash('profileError');
if (isset($profileError)) {
    echo "<div class='alert alert-danger' role='alert'>".$profileError."</div>";
}


?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="https://www.yiiframework.com">Get started with Yii</a></p>
    </div>

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
                <h2>Nach Usern suchen</h2>

                <p><?php /*
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
            </div>
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
</div>
