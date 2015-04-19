<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\NP_STAFF;
use app\models\WA_PROVINCE;
use app\models\WA_AMPHOE;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
class TestgraphController extends AppController
{
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
    //----------------------------------------------------------------------------------------------------------------------
    public function actionIndex()
    {
    	$request = Yii::$app->request;
        return $this->render('index');    
    }
  
    //----------------------------------------------------------------------------------------------------------------------
    public function actionDataGraph()
    {

    	$x = array('รอบเดือนที่ 3','รอบเดือนที่ 6','รอบเดือนที่ 9','รอบเดือนที่ 12');
    	$y = array(0.03,0.06,0.09,0.12);
    	
    	$graph_data = array('X'=>$x, 'Y'=>$y);
    	
    	echo json_encode($graph_data);
    	
    }
}
