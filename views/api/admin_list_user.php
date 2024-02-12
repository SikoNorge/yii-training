<?php

use yii\data\ArrayDataProvider;
use yii\base\InvalidConfigException;
use yii\grid\GridView;
use yii\helpers\Html;


try {
    $this->registerCssFile('@web/css/api.css');
} catch (InvalidConfigException $e) {
}

$this->registerJs("
    $(document).on('click', '.get-access-token', function() {
        var userId = $(this).data('user-id'); // UserId aus dem Datenattribut extrahieren
        var button = $(this); // Referenz auf den angeklickten Button
        var cell = button.closest('td'); // Zelle, die den Button enthält
        $.ajax({
            method: 'POST',
            url: '" . Yii::$app->urlManager->createUrl(['/api/user-password-and-token']) . "', // URL zur Controller-Action
            data: {userId: userId}, // Daten senden, hier nur die UserId
            success: function(response) {
                var tokenSpan = $('<span>', {
                    'class': 'access-token',
                    'style': 'white-space: pre-wrap; max-width: 60vh; display: block; overflow-wrap: break-word;'
                }).text('Access Token: ' + response); // Token-Span erstellen und Text einfügen
                cell.append(tokenSpan); // Token in die Zelle einfügen
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error); // Fehler in der Konsole anzeigen
            }
        });
    });
");

// Überschrift ausgeben
echo "<h1>Benutzerliste</h1>";



// GridView verwenden, um die Benutzerliste anzuzeigen
$dataProvider = new ArrayDataProvider([
    'allModels' => $userList['users'],
    'pagination' => [
        'pageSize' => 5,
    ],
    'sort' => [
        'attributes' => [
            'userId', // Spalte nach Benutzer-ID sortierbar machen
            'registrationDate', // Spalte nach Registrierungsdatum sortierbar machen
            'bankConnectionCount', // Spalte nach Anzahl der Bankverbindungen sortierbar machen
        ],
    ],
]);
//TODO FARBE VON SORTIERPFEIL ÄNDERn
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered text-white'],
    'columns' => [
        [
            'attribute' => 'userId',
            'label' => 'Benutzer-ID',
            'format' => 'raw',
            'value' => function ($data) {
                return Html::a($data['userId'], ['user/view', 'id' => $data['userId']], ['style' => 'color: white;']);
            },
        ],
        [
            'attribute' => 'registrationDate',
            'label' => 'Registrierungsdatum',
            'headerOptions' => ['class' => 'text-center', 'style' => 'color: white;'], // Textfarbe ändern
        ],
        [
            'attribute' => 'bankConnectionCount',
            'label' => 'Anzahl der Bankverbindungen',
            'headerOptions' => ['class' => 'text-center', 'style' => 'color: white;'], // Textfarbe ändern
        ],
        [
            'attribute' => 'isLocked',
            'format' => 'raw',
            'value' => function ($data) {
                return $data['isLocked'] ? 'Ja' : 'Nein';
            },
            'label' => 'Gesperrt',
            'headerOptions' => ['class' => 'text-center', 'style' => 'color: black;'], // Textfarbe ändern
        ],
        [
            'label' => 'User Access Token',
            'headerOptions' => ['class' => 'text-center', 'style' => 'color: black;'], // Textfarbe ändern
            'format' => 'raw',
            'value' => function ($data) {
                return Html::button('Get Access Token', [
                    'class' => 'btn btn-primary get-access-token',
                    'data-user-id' => $data['userId'], // Datenattribut mit der UserId
                ]);
            },
        ],
    ],
    'headerRowOptions' => ['class' => 'custom-header-cell'], // Diese Zeile hinzufügen

    'pager' => [
        'options' => ['class' => 'pagination justify-content-center mb-4'],
        'prevPageLabel' => '<span class="visually-hidden">Previous</span>&laquo;',
        'nextPageLabel' => '&raquo;<span class="visually-hidden">Next</span>',
        'activePageCssClass' => 'disabled bg-dark',
        'disabledPageCssClass' => 'disabled',
        'maxButtonCount' => 5,
        'firstPageLabel' => 'Erste',
        'lastPageLabel' => 'Letzte',
        'firstPageCssClass' => 'page-link',
        'lastPageCssClass' => 'page-link',
        'prevPageCssClass' => 'page-link',
        'nextPageCssClass' => 'page-link',
        'pageCssClass' => 'page-link',
    ],
]);



echo $accessToken;
