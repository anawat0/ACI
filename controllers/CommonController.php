<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\AjaxController;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\WA_REGION;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;

class CommonController extends AjaxController
{
 
    public function actionGetddlprovinceregion()
    {        
    	$request = Yii::$app->request;
    	$region = $request->post('region', ''); $region = (empty($region)?$request->get('region', ''):$region);
    	$result = WA_PROVINCE::find()->where(['REGION_CODE'=>$region])->asArray()->all();
    	$this->ajaxResponse($result);        
    }
    
    public function actionGetddlprovince()
    {
    	$result = WA_PROVINCE::find()->asArray()->all();
    	$this->ajaxResponse($result);
    }
    
    public function actionGetddlampore()
    {
    	$request = Yii::$app->request;
    	$province = $request->post('province', ''); $province = (empty($province)?$request->get('province', ''):$province);
    	$result = WA_AMPHOE::find()->where(['PROVINCE_CODE'=>$province])->asArray()->all();
    	$this->ajaxResponse($result);
    }
}
