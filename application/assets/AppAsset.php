<?php

namespace Application\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/bootstrap.min.css',
        '/fontawesome/css/all.min.css',
        '/css/templatemo-style.css',
    ];
    public $js = [
        '/js/jquery-2.2.4.min.js',
        '/js/plugins.js',
    ];
    public $depends = [
    ];
}
