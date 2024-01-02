<?php
use dosamigos\chartjs\ChartJs;
use app\models\User;

// Beispiel-Daten für das Diagramm (Anzahl der Benutzer pro user_type)
$userCounts = User::find()
    ->select(['user_type', 'COUNT(*) AS count'])
    ->groupBy('user_type')
    ->asArray()
    ->all();

$labels = [];
$data = [];

foreach ($userCounts as $userCount) {
    $labels[] = $userCount['user_type'];
    $data[] = $userCount['count'];
}

$userTypeData = [
        'labels' => $labels,
        'data' => $data,
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
<h1>Admin Dashboard</h1>
<div style="display: flex;
    justify-content: center;
    align-items: center;
    height: 50%; /* Höhe des Diagramms */
    width: 50%;
    margin: auto">
    <canvas id="myPieChart"></canvas>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
    // Diese Daten könnten von deiner Yii2-Anwendung kommen
    var userTypesData = <?= json_encode($userTypeData); ?>; // $userTypesData ist ein Beispiel für deine Daten

    var labels = userTypesData.labels; // ["Admin", "User", ...]
    var data = userTypesData.data; // [10, 20, ...]

    var pieChartData = {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                // Füge so viele Farben hinzu, wie es User-Typen gibt
            ],
            hoverOffset: 4 // Abstand bei Mouseover-Effekt
        }]
    };

    var ctx = document.getElementById('myPieChart').getContext('2d');
    var myPieChart = new Chart(ctx, {
        type: 'pie', // Typ des Diagramms: Pie-Chart
        data: pieChartData,
        options: {
            onClick: function (evt, item) {
                if (item.length > 0) {
                    var index = item[0].index;
                    var label = labels[index]; // Der angeklickte User-Typ
                    if (label === 'user') {
                        window.location.href = '/index.php?r=users%2Findex&UserSearch[user_type]=user';
                    } else window.location.href = '/index.php?r=users%2Findex&UserSearch[user_type]=admin';
                }
            }
        }
    });
</script>

</body>
</html>
