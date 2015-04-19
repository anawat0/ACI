<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\helpers\Url;
use yii\helpers\BaseArrayHelper;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\WA_GROUP;

class WaAnnouncementController extends AppController
{
	public $title = 'WA003 แก้ไขข้อความประชาสัมพันธ์';
	
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],

        ];
    }
    public function actionIndex()
    {
    	$request = Yii::$app->request;
    	
    	$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
    	
    	$this->addBreadCrumb($this->title);
    	
    	$params['rtparams']=$rtparams;
        return $this->render('index', $params);
    }
    
    public function actionUploadimg(){
    	
    }
    
}
