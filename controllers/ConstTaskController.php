<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Authentication;
use yii\web\AppController;
use yii\web\Utils;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use himiklab\jqgrid\actions\JqGridActiveAction;
use himiklab\jqgrid\jqGridResponse;
use app\models\CONST_TASK;

class ConstTaskController extends AppController
{
    public $title = 'CON001 โครงการงานก่อสร้างสนามกีฬา - งวดงาน ของโครงการงานก่อสร้างสนามกีฬา';
	
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
        
        $this->addBreadCrumb('CON001 โครงการงานก่อสร้างสนามกีฬา', Url::to(['constproj/', 'rtparams'=>$rtparams]));
    	$this->addBreadCrumb('งวดงาน');
    	
    	$params = array();
    	$constproj = $request->post('constproj', ''); $constproj = (empty($constproj)?$request->get('constproj', ''):$constproj);
    	$params['constproj']=$constproj;
    	$params['rtparams'] = $rtparams;
    	
    	
        return $this->render('index', $params);
    }
    public function actionGridview()
    {
    	$request = Yii::$app->request;

        // Authentication
        $user_info = Authentication::getAuthenInfo();
        $user_id = $user_info->EMAIL;

		//Parameter from jqGrid
		$oper = $request->post('oper', ''); $oper = (empty($oper)?$request->get('oper', ''):$oper);
		$page = $request->post('page', ''); $page = (empty($page)?$request->get('page', ''):$page);
		$rows = $request->post('rows', ''); $rows = (empty($rows)?$request->get('rows', ''):$rows);
		$sidx = $request->post('sidx', ''); $sidx = (empty($sidx)?$request->get('sidx', ''):$sidx);
		$sord = $request->post('sord', ''); $sord = (empty($sord)?$request->get('sord', ''):$sord);
		$id = $request->post('id', ''); $id = (empty($id)?$request->get('id', ''):$id);
		$isSearch = $request->post('_search', ''); $isSearch = (empty($isSearch)?$request->get('_search', ''):$isSearch);
		$isSearch = filter_var($isSearch, FILTER_VALIDATE_BOOLEAN);
		$filters = $request->post('filters', ''); $filters = (empty($filters)?$request->get('filters', ''):$filters);

		//Input parameter from jqGrid Form.
		$taskNameTh = $request->post('taskNameTh');
		$startDate = $request->post('startDate');
		$endDate = $request->post('endDate');
		$budget = $request->post('budget');
		$status = $request->post('status');
		
		//Parameter from another pages.
		$const_proj = $request->post('constproj'); $const_proj = (empty($const_proj)?$request->get('constproj'):$const_proj);
		
		//response parameter to jqGrid
        $result = '';
    	
    	switch($oper){
    		case 'request' :
    			$offset = ($page-1)*$rows;
    			
    			$where_causes = array();
    			$where = 'and';
    			array_push($where_causes, $where);
    			array_push($where_causes, 'CONST_PROJ_ID='.$const_proj);
    			if($isSearch){
    				$filters = json_decode($filters);
    				// print_r($filters);
    				switch($filters->groupOp){
    					case 'AND':
    						$where = 'and';
    						break;
    					case 'OR':
    						$where = 'or';
    						break;
    				}

    				foreach($filters->rules as $conditions){
    					array_push($where_causes, $conditions->field.$this->filtersOperand[$conditions->op]."'".$conditions->data."'" );
    				}
    			}
    			
    			$count = CONST_TASK::find()->where($where_causes)->count();
    			$result = CONST_TASK::find()->where($where_causes)->orderBy($sidx.' '.$sord)->offset($offset)->limit($rows)->asArray()->all();
    			$countResult = count($result);
                $seq = $rows * ($page - 1);

    			$response = new jqGridResponse();
    			$response->page = $page;
    			$response->total = intval(ceil($count/$rows));
    			$response->records = $count;
    			
				for($i=0; $i<$countResult; $i++){
                    $seq++;

					array_push($response->rows, array('id'=>$result[$i]['CONST_TASK_ID'], 
													'cell'=>array($seq,
																	$result[$i]['TASK_NAME_TH'],
																	$result[$i]['START_DATE'],//$result[$i]['START_DATE'],
																	$result[$i]['END_DATE'],//$result[$i]['END_DATE'],
																	$result[$i]['BUDGET'],
																	$result[$i]['STATUS'],
                                                                    'edit'
																	)));
				}
				
    			$result = json_encode($response);
    			
	    		break;
	    		
    		case 'edit':
                try {
        			//To update an existing customer record
    				$constTask = CONST_TASK::findOne($id);
    				$constTask->TASK_NAME_TH = $taskNameTh ;
    				$constTask->START_DATE = Utils::toDate($startDate);
                    $constTask->END_DATE = Utils::toDate($endDate);
                    $constTask->BUDGET = $budget ;
                    $constTask->STATUS = Utils::getStatus($status);                        
    				$constTask->LAST_UPD_USER_ID = $user_id;
    				$constTask->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
                                    
    				$response = new jqGridResponse();
                    if ($constTask->save()) {  
                        $response->success(['id'=>$constTask->CONST_TASK_ID]);
                    } else {
                        $response->error($constTask->getErrors());
                    }
                } catch (Exception $e) {
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

                $result = $response->response_encode();
    			break;
		    case 'add':
                try {
    				$constTask = new CONST_TASK();
    				$constTask->CONST_TASK_ID = CONST_TASK::getNewID();
    				$constTask->CONST_PROJ_ID = $const_proj;
    				$constTask->TASK_NAME_TH = $taskNameTh ;
    				$constTask->START_DATE = Utils::toDate($startDate);
                    $constTask->END_DATE = Utils::toDate($endDate);
                    $constTask->BUDGET = $budget ;
                    $constTask->STATUS = Utils::getStatus($status);
                    $constTask->PROGRESS = 0;
    				$constTask->CREATE_USER_ID = $user_id;
    				$constTask->CREATE_TIME = new \yii\db\Expression('SYSDATE');
    				$constTask->LAST_UPD_USER_ID = $user_id;
    				$constTask->LAST_UPD_TIME = new \yii\db\Expression('SYSDATE');
                                    
    				$response = new jqGridResponse();
                    if ($constTask->save()) {  
                        $response->success(['id'=>$constTask->CONST_TASK_ID]);
                    } else {
                        $response->error($constTask->getErrors());
                    }
                } catch (Exception $e) {
                    $response->error([Utils::getOracleErrorMsg($e->errorInfo[1])]);
                }

                $result = $response->response_encode();
				break;
			case 'del':
                try {
                    $arrId = array();
                    $arrId = explode(',', $id);
                    $response = new jqGridResponse();

                    CONST_TASK::deleteAll(['in', 'CONST_TASK_ID', $arrId]);

                    $response->success(['message' => 'ลบข้อมูลสำเร็จ']);
                } catch (Exception $e) {
                    $response->error(Utils::getOracleErrorMsg($e->errorInfo[1]));
                }
                
                $result = $response->response_encode();
				break;
    	}
    	
    	echo $result;

    }
}
