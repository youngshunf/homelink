<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;
/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'AdminLTE/dist/css/AdminLTE.min.css',
        'AdminLTE/dist/css/skins/_all-skins.css',
        'icheck/skins/all.css',
    ];
    public $jsOptions=[
        'position'=> View::POS_HEAD
    ];
    public $js = [
        'icheck/icheck.min.js',
        'AdminLTE/dist/js/app.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
