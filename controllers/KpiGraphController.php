<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\AppController;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\KPI_REC;
use app\models\KPI_DIMENTION;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Utils;
use yii\helpers\BaseArrayHelper;

class KpiGraphController extends AppController
{
	public $title = 'KPI003 สรุปผล ตัวชี้วัดผลการปฏิบัติราชการ';
	public $enableCsrfValidation = false;
                
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
        $rtparams = $request->post('rtparams'); $rtparams = (empty($rtparams)?$request->get('rtparams'):$rtparams);
        $this->addBreadCrumb('KPI003 สรุปผล ตัวชี้วัดผลการปฏิบัติราชการ');
        
        $params = array('rtparams'=>$rtparams);
        return $this->render('index', $params);
    }
    //----------------------------------------------------------------------------------------------------------------------
    public function actionShowGraph()
    {
    	$request = Yii::$app->request;
    	 
    	$year = $request->post('year');
    	$month = $request->post('month');
    	$dimention = $request->post('dimention');
    	
    	$queryFstId = 'SELECT C.KPI_FST_ID, F.FST_NAME_TH
    	                         FROM KPI_DIMENTION D,
    	                              KPI_REC C
    			                 LEFT JOIN KPI_FST F ON F.KPI_FST_ID = C.KPI_FST_ID
    							WHERE D.BUDGET_YEAR = '.$year.'
    							  AND C.KPI_DIMENTION_ID = '.$dimention.'
    	  						  AND D.KPI_DIMENTION_ID = C.KPI_DIMENTION_ID
    	  						 GROUP BY C.KPI_FST_ID,F.FST_NAME_TH';
    	
    	$command = Yii::$app->db->createCommand($queryFstId);
    	$rsQeryFstId= $command->queryAll();
    	
    	return json_encode($rsQeryFstId);
    }
    //----------------------------------------------------------------------------------------------------------------------
    public function actionDataGraph()
    {
    	$request = Yii::$app->request;
    	 
    	$year = $request->post('year');
    	$month = $request->post('month');
    	$dimention = $request->post('dimention');
    	$fstId = $request->post('fstId');
    	
    	$query = ' SELECT C.QUATER,SUM(C.WT) AS WT
  					 FROM KPI_DIMENTION D,
					      KPI_REC C
					WHERE D.BUDGET_YEAR = '.$year.' --Parameter
				  AND D.KPI_DIMENTION_ID = '.$dimention.' --Parameter
				  	  AND C.KPI_FST_ID = '.$fstId.' --Parameter
 					  AND D.KPI_DIMENTION_ID = C.KPI_DIMENTION_ID
					  AND C.QUATER = 1
				 GROUP BY C.QUATER
 					UNION
					SELECT C.QUATER,SUM(C.WT) AS WT
					 FROM KPI_DIMENTION D,
 					      KPI_REC C
 					WHERE D.BUDGET_YEAR = '.$year.' --Parameter
				  AND D.KPI_DIMENTION_ID = '.$dimention.' --Parameter
				  AND C.KPI_FST_ID = '.$fstId.' --Parameter
				  AND D.KPI_DIMENTION_ID = C.KPI_DIMENTION_ID
					  AND C.QUATER = 2
				 GROUP BY C.QUATER
				UNION
 					SELECT C.QUATER,SUM(C.WT) AS WT
 					 FROM KPI_DIMENTION D,
					      KPI_REC C
					WHERE D.BUDGET_YEAR = '.$year.' --Parameter
				  AND D.KPI_DIMENTION_ID = '.$dimention.' --Parameter
				  	  AND C.KPI_FST_ID = '.$fstId.' --Parameter
 					  AND D.KPI_DIMENTION_ID = C.KPI_DIMENTION_ID
 					  AND C.QUATER = 3
					 GROUP BY C.QUATER
					UNION
					SELECT C.QUATER,SUM(C.WT) AS WT
					 FROM KPI_DIMENTION D,
					      KPI_REC C
				WHERE D.BUDGET_YEAR = '.$year.' --Parameter
					  AND D.KPI_DIMENTION_ID = '.$dimention.' --Parameter
					  AND C.KPI_FST_ID = '.$fstId.' --Parameter
 					  AND D.KPI_DIMENTION_ID = C.KPI_DIMENTION_ID
 					  AND C.QUATER = 4
					GROUP BY C.QUATER';
    	
    	$command = Yii::$app->db->createCommand($query);
    	$result= $command->queryAll();
    	
    	//print_r($result); exit();
    	
    	switch($month){
    		case '1':
    			$x = array('ไตรมาสที่ 1');
    	        $y = array(intval($result[0]['WT']));
    			break;
    		case '2':
    			$x = array('ไตรมาสที่ 2');
    	        $y = array(intval($result[0]['WT']));
    			break;
    		case '3':
    			$x = array('ไตรมาสที่ 3');
    			$y = array(intval($result[0]['WT']));
    			break;
    		case '4':
    			$x = array('ไตรมาสที่ 4');
    	        $y = array(intval($result[0]['WT']));
    			break;
    		default:
    			$x = array('ไตรมาสที่ 1','ไตรมาสที่ 2','ไตรมาสที่ 3','ไตรมาสที่ 4');

    			$y1 = (empty($result[0]['WT'])?0:intval($result[0]['WT']));
    			$y2 = (empty($result[1]['WT'])?0:intval($result[1]['WT']));
    			$y3 = (empty($result[2]['WT'])?0:intval($result[2]['WT']));
    			$y4 = (empty($result[3]['WT'])?0:intval($result[3]['WT']));	
    			
    	        $y = array($y1,$y2,$y3,$y4);
    			break;
    	}
    	
    	$graph_data = array('X'=>$x, 'Y'=>$y);
    	echo json_encode($graph_data);
    }
    //----------------------------------------------------------------------------------------------------------------------
    


    public function actionGetddldimention()
    {
    	$request = Yii::$app->request;
    	$budget_year = $request->post('budget_year', ''); $budget_year = (empty($budget_year)?$request->get('budget_year', ''):$budget_year);
    	$result = KPI_DIMENTION::find()->where(['BUDGET_YEAR'=>$budget_year])->asArray()->all();
    	echo json_encode($result);
    }
}
