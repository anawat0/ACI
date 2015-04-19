<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\AppController;
use yii\web\Utils;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\NP_WORK_GROUP;

class NpWorkProvinceController extends AppController
{
	public $title = 'หัวข้อการปฎิบัติงาน';
    public $enableCsrfValidation = false;
    public $imagePath = 'images/welcome';

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
    	//$rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)? $request->get('rtparams'): $rtparams);
    	 
    	//$npwork = SB_PROJ_LAUN::find()->innerJoinWith('sbProj')->orderBy('SB_PROJ_LAUN.SB_PROJ_LAUN_ID')->all();
    	$npwork  = NP_WORK_GROUP::find()->all();

    	//$model = NP_WORK_GROUP::findOne($id);

    	$this->addBreadCrumb('NP003 บันทึกผลการปฏิบัติงานประจำเดือน (จพล. ประจำจังหวัด)');
    	
    	$params = array('npwork' => $npwork);
    	 
    	return $this->render('index', $params);
    }
}
