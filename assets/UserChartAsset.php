<?php
namespace app\assets;

use yii\web\AssetBundle;

class UserChartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'assets/custom/userChart.js', // Passe den Pfad entsprechend an
    ];
    public $depends = [
        \yii\web\JqueryAsset::class,
        // Andere Abhängigkeiten hier hinzufügen, falls benötigt
    ];
}