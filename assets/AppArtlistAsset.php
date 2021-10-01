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
		//'https://static.artlist.pro/css/style.css?v=1.446',
        'web/artlist/css/style.css',
    ];

    public $js = [
        //'js/static/jquery2.js',
        //'https://static.artlist.pro/js/components/jquery.formstyler.js',
        'js/static/jquery.formstyler.js',
        //'https://static.artlist.pro/js/components/slick.js',
        'js/static/slick.js',
        //'https://static.artlist.pro/js/components/jquery.form.min.js',
        'js/static/jquery.form.min.js',
        //'https://static.artlist.pro/js/components/jquery.webui-popover.min.js',
        'js/static/jquery.webui-popover.min.js',
        //'https://static.artlist.pro/js/components/jquery.cookie.min.js',
        'js/static/jquery.cookie.min.js',

        // Я ХЗ ЗАЧЕМ ЭТО НАДО кроме стрелочек в ЛС, если их > 1, ОТКЛЮЧИЛ 30.05.2020 //'https://static.artlist.pro/js/components/swiper.js', 

        //'https://static.artlist.pro/js/custom.js?v=1.441',
        'js/static/custom.js',

		//'https://static.artlist.pro/js/bodyScrollLock.js',
        'js/static/bodyScrollLock.js',
        //'https://static.artlist.pro/js/lazysizes.min.js',
        'js/static/lazysizes.min.js',
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
