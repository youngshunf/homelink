<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class FlatlabAsset extends AssetBundle
{
    public $basePath = '@webroot/flatlab';
    public $baseUrl = '@web/flatlab';
    public $css = [
        'css/bootstrap-reset.css',
        'assets/font-awesome/css/font-awesome.css',
        'assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css',
        'css/owl.carousel.css',
        'css/style.css',
        'css/style-responsive.css',
    ];
 /*    public $jsOptions=[
    'position'=> View::POS_HEAD
    ]; */
    public $js = [
    	'js/jquery.scrollTo.min.js', 
    	'js/jquery.nicescroll.js',
    	'js/jquery.sparkline.js',
    	'assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js',
    	'js/owl.carousel.js',
    	'js/jquery.customSelect.min.js',
    	'js/respond.min.js',
    	'js/jquery.dcjqaccordion.2.7.js',
    	'js/sparkline-chart.js',
    	'js/easy-pie-chart.js',
    	'assets/jquery-knob/js/jquery.knob.js',
    	'js/count.js',
    	'js/jquery.dcjqaccordion.2.7.js',
    	'js/jquery.scrollTo.min.js',
    	'js/jquery.nicescroll.js',
    	'js/common-scripts.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
