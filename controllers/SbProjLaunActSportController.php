<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Authentication;
use yii\web\AppController;
use yii\helpers\Url;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\WA_SPORT;
use app\models\SB_PROJ_LAUN_ACT;
use app\models\SB_PROJ_LAUN_ACT_SPORT;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;

class SbProjLaunActSportController extends AppController
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

    public function actionIndex()
    {
    	$request = Yii::$app->request;

    	$model = new SB_PROJ_LAUN_ACT_SPORT(); 
    	$sb_proj_laun_act_id = $request->post('sb_proj_laun_act_id'); $sb_proj_laun_act_id = (empty($sb_proj_laun_act_id)? $request->get('sb_proj_laun_act_id'): $sb_proj_laun_act_id);
    	$waSports = WA_SPORT::find()->all();
    	$sbProjLaunAct = SB_PROJ_LAUN_ACT::findOne($sb_proj_laun_act_id);
    	$sbProjLaunActSports= SB_PROJ_LAUN_ACT_SPORT::findAll(['SB_PROJ_LAUN_ACT_ID' => $sb_proj_laun_act_id]);

        //Make prepair breadcrumb.
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
        $this->addBreadCrumb('SB002 โครงการที่กำลังดำเนินการ สกฬ.', Url::to(['sbprojlaun/']));
        $this->addBreadCrumb('รูปแบบกิจกกรม', Url::to(['sbprojlaunact/', 'sb_proj_laun_id' => $sbProjLaunAct['SB_PROJ_LAUN_ID'], 'rtparams' => $rtparams]));
         $this->addBreadCrumb('ชนิดของกีฬา');


        $params = array('model' => $model,
                    'sb_proj_laun_act_id' => $sb_proj_laun_act_id,
                    'sbProjLaunAct' => $sbProjLaunAct, 
                    'waSports' => $waSports, 
                    'sbProjLaunActSports' => $sbProjLaunActSports);

        return $this->render('index', $params);
    }

    public function actionSaved() 
    {
    	try {
	    	$request = Yii::$app->request;
	    	$connection = Yii::$app->db;

            // Authentication
            $user_info = Authentication::getAuthenInfo();
            $user_id = $user_info->EMAIL;
			
	    	// Parameters.
	    	$sbProjLaunActId = $request->post('SB_PROJ_LAUN_ACT_ID');
	    	$waSportIds = $request->post('WA_SPORT_ID');

	    	// Ref. http://www.yiiframework.com/forum/index.php/topic/57182-transaction-in-yii2/ 
	    	$transaction = $connection->beginTransaction(); 
    		SB_PROJ_LAUN_ACT_SPORT::deleteAll(['SB_PROJ_LAUN_ACT_ID' => $sbProjLaunActId]);

    		// catch null for foreach Ref. http://stackoverflow.com/questions/3446538/cleanest-way-to-skip-a-foreach-if-array-is-empty
	    	foreach ( (array)$waSportIds as $value ) {
	    		$sbProjLaunActSports = new SB_PROJ_LAUN_ACT_SPORT();
	    		$sbProjLaunActSports->SB_PROJ_LAUN_ACT_SPORT_ID = SB_PROJ_LAUN_ACT_SPORT::getNewID();
	    		$sbProjLaunActSports->SB_PROJ_LAUN_ACT_ID = $sbProjLaunActId;
	    		$sbProjLaunActSports->WA_SPORT_ID = $value;
	    		$sbProjLaunActSports->STATUS = 'A';
	    		$sbProjLaunActSports->CREATE_USER_ID = $user_id;
				$sbProjLaunActSports->CREATE_TIME = new \yii\db\Expression('SYSDATE');
				$sbProjLaunActSports->LAST_UPD_USER_ID = $user_id;
				$sbProjLaunActSports->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
	    		if ($sbProjLaunActSports->save()) {
                } else {
                    throw new Exception($sbProjLaunActSports->getErrors());
                    break;
                }
	    	}

	    	$transaction->commit();

	    	Yii::$app->getSession()->setFlash('success', 'บันทึกข้อมูลสำเร็จ');
    	} catch (Exception $e) {
    		$transaction->rollback();
    		
    		Yii::$app->getSession()->setFlash('error', 'เกิดข้อผิดพลาด');
    	} finally {
    		return $this->redirect(Url::to(['sbprojlaunactsport/', 'sb_proj_laun_act_id' => $sbProjLaunActId ]));
    	}
    }
}
