<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = array(
    		'position' => \yii\web\View::POS_HEAD
    );
    public $js = [
    // 'assets/js/jquery-2.1.1.js',
    'assets/jquery-ui/jquery-ui.js',
   // 'assets/mmenu/src/js/jquery.mmenu.min.all.js',
	'assets/metisMenu/js/metismenu.min.js',
	'assets/metisMenu/js/bootstrap.min.js',
	'assets/metisMenu/js/bootstrap-dialog.min.js',
	'assets/ajaxfileupload/ajaxfileupload.js',
	'assets/js/common.js',
    'assets/gmaps/map.js',
    // 'assets/gmaps/jquery.ui.map.js', ****** problem
    'assets/gmaps/common.js',
    'assets/gmaps/controls.js',
    'assets/gmaps/infowindow.js',
    'assets/gmaps/map.js',
    'assets/gmaps/marker.js',
    'assets/gmaps/onion.js',
    'assets/gmaps/stats.js',
    'assets/gmaps/util.js',
    'assets/gmaps/overlay.js',
    'assets/select2/select2.min.js',
    'assets/js/loading.js'
    		];
    public $css = [
       'css/site.css',
       'assets/mmenu/src/css/jquery.mmenu.all.css',
       'assets/jquery-ui/jquery-ui.css',
       'assets/mmenu/css/demo.css',
       'assets/metisMenu/css/demo.css',
       'assets/metisMenu/css/font-awesome.min.css',
       'assets/metisMenu/css/metismenu.min.css',
       'assets/metisMenu/css/prism.min.css',
       'assets/metisMenu/css/bootstrap.min.css',
       'assets/metisMenu/css/bootstrap-dialog.min.css',
       'assets/select2/select2.css',
       'css/loading.css'
       
    ];


    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
