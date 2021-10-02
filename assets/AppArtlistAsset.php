<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppArtlistAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'web/artlist/css/style.css',
    ];

    public $js = [
        'web/artlist/js/jquery.formstyler.js',
        'web/artlist/js/slick.js',
        'web/artlist/js/jquery.form.min.js',
        'web/artlist/js/jquery.webui-popover.min.js',
        'web/artlist/js/jquery.cookie.min.js',
        'web/artlist/js/custom.js',
        'web/artlist/js/bodyScrollLock.js',
        'web/artlist/js/lazysizes.min.js',
        // Я ХЗ ЗАЧЕМ ЭТО НАДО кроме стрелочек в ЛС, если их > 1, ОТКЛЮЧИЛ 30.05.2020 //'https://static.artlist.pro/js/components/swiper.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = [
		'async' => 'async',
		//'defer' => 'defer',
        'position' => \yii\web\View::POS_HEAD
        //'position' => \yii\web\View::POS_READY
    ];
}
